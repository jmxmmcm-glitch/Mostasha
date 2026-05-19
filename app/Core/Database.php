<?php

namespace App\Core;

/**
 * Database class — PDO/PostgreSQL implementation with a mysqli-compatible facade.
 *
 * This wrapper preserves the original mysqli-style API used throughout the app
 * (prepare, bind_param, execute, get_result, fetch_assoc, num_rows, query,
 * multi_query) so we did not have to rewrite every Model/Controller.
 *
 * Internally it speaks PostgreSQL via PDO. It rewrites `?` placeholders to
 * `$1, $2, ...` for pgsql, and rewrites a few MySQL-specific bits in SQL
 * strings (AUTO_INCREMENT, ENUM, backticks, ON DUPLICATE KEY ...) to keep
 * the original schema definitions in legacy models working.
 */
class Database
{
    public \PDO $conn;

    public function __construct(array $config)
    {
        // Prefer DATABASE_URL (Render style) when present
        $url = getenv('DATABASE_URL') ?: ($config['url'] ?? null);

        if ($url) {
            $parts = parse_url($url);
            $host = $parts['host'] ?? 'localhost';
            $port = $parts['port'] ?? 5432;
            $user = $parts['user'] ?? '';
            $password = isset($parts['pass']) ? urldecode($parts['pass']) : '';
            $dbname = isset($parts['path']) ? ltrim($parts['path'], '/') : '';
            $sslmode = 'require';

            // Honor ?sslmode=... in query string
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $q);
                if (!empty($q['sslmode'])) $sslmode = $q['sslmode'];
            }

            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode={$sslmode}";
        } else {
            $host = $config['host'] ?? 'localhost';
            $port = $config['port'] ?? 5432;
            $user = $config['user'] ?? 'postgres';
            $password = $config['password'] ?? '';
            $dbname = $config['dbname'] ?? 'postgres';
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        }

        try {
            $this->conn = new \PDO($dsn, $user, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            $this->conn->exec("SET NAMES 'UTF8'");
        } catch (\PDOException $e) {
            die("Database Connection failed: " . htmlspecialchars($e->getMessage()));
        }
    }

    /**
     * Rewrites MySQL-flavoured SQL into PostgreSQL.
     * Best-effort translation for the limited set of statements used in this app.
     */
    public static function translateSql(string $sql): string
    {
        // Strip backticks
        $sql = str_replace('`', '"', $sql);

        // AUTO_INCREMENT INT PRIMARY KEY  ->  SERIAL PRIMARY KEY
        $sql = preg_replace('/INT\s+AUTO_INCREMENT\s+PRIMARY\s+KEY/i', 'SERIAL PRIMARY KEY', $sql);
        $sql = preg_replace('/INT\s+AUTO_INCREMENT/i', 'SERIAL', $sql);

        // ENUM('a','b',...) NOT NULL  ->  VARCHAR(50) NOT NULL  (simple compat)
        $sql = preg_replace_callback(
            '/ENUM\s*\(([^)]+)\)/i',
            function ($m) { return 'VARCHAR(50)'; },
            $sql
        );

        // DATETIME -> TIMESTAMP
        $sql = preg_replace('/\bDATETIME\b/i', 'TIMESTAMP', $sql);

        // TINYINT -> SMALLINT
        $sql = preg_replace('/\bTINYINT(\([^)]*\))?/i', 'SMALLINT', $sql);

        // ENGINE=... CHARSET=... COLLATE=...
        $sql = preg_replace('/\sENGINE\s*=\s*\w+/i', '', $sql);
        $sql = preg_replace('/\sDEFAULT\s+CHARSET\s*=\s*\w+/i', '', $sql);
        $sql = preg_replace('/\sCHARACTER\s+SET\s+\w+/i', '', $sql);
        $sql = preg_replace('/\sCOLLATE\s+\w+/i', '', $sql);

        // INSERT IGNORE -> INSERT ... ON CONFLICT DO NOTHING
        if (preg_match('/^\s*INSERT\s+IGNORE\b/i', $sql)) {
            $sql = preg_replace('/^\s*INSERT\s+IGNORE\b/i', 'INSERT', $sql);
            // append ON CONFLICT DO NOTHING (before any trailing semicolon)
            $sql = rtrim($sql, "; \t\n\r");
            $sql .= ' ON CONFLICT DO NOTHING';
        }

        // Remove "USE dbname;" — has no meaning in pgsql session
        $sql = preg_replace('/^\s*USE\s+\w+\s*;?\s*/im', '', $sql);

        // CREATE DATABASE IF NOT EXISTS ...  — strip (db is already created on Render)
        $sql = preg_replace('/CREATE\s+DATABASE\s+IF\s+NOT\s+EXISTS[^;]*;?/i', '', $sql);

        return $sql;
    }

    /**
     * Rewrite `?` placeholders to `$1, $2, ...` for PostgreSQL prepared statements.
     */
    public static function rewritePlaceholders(string $sql): string
    {
        $i = 0;
        return preg_replace_callback('/\?/', function () use (&$i) {
            $i++;
            return '$' . $i;
        }, $sql);
    }

    public function prepare($sql)
    {
        $sql = self::translateSql($sql);
        $sql = self::rewritePlaceholders($sql);
        try {
            $stmt = $this->conn->prepare($sql);
            return new Statement($stmt);
        } catch (\PDOException $e) {
            error_log("[DB prepare] " . $e->getMessage() . " SQL: " . $sql);
            return false;
        }
    }

    /**
     * Direct query — runs translated SQL. May contain multiple statements
     * (used by setup_db.php for the schema import).
     */
    public function query($sql)
    {
        $sql = self::translateSql($sql);
        try {
            $stmt = $this->conn->query($sql);
            if ($stmt === false) return false;
            return new Result($stmt);
        } catch (\PDOException $e) {
            error_log("[DB query] " . $e->getMessage() . " SQL: " . $sql);
            return false;
        }
    }

    /**
     * Run multiple statements separated by `;` — for schema import.
     */
    public function multi_query(string $sql): bool
    {
        $sql = self::translateSql($sql);
        // Split on semicolons that are not inside quotes (best effort: schema file is simple)
        $statements = preg_split('/;\s*(?=(?:[^\']*\'[^\']*\')*[^\']*$)/', $sql);
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if ($stmt === '' || preg_match('/^--/', $stmt)) continue;
            try {
                $this->conn->exec($stmt);
            } catch (\PDOException $e) {
                error_log("[DB multi_query] " . $e->getMessage() . " SQL: " . $stmt);
                // continue with remaining statements
            }
        }
        return true;
    }
}

/**
 * mysqli_stmt-compatible facade over a PDOStatement.
 */
class Statement
{
    private \PDOStatement $stmt;
    private array $params = [];
    private ?Result $lastResult = null;

    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * bind_param("ssi", $a, $b, $c)
     * We accept by-value here; legacy code re-binds before each execute().
     */
    public function bind_param(string $types, &...$values): bool
    {
        $this->params = [];
        for ($i = 0; $i < strlen($types); $i++) {
            $t = $types[$i];
            $v = $values[$i] ?? null;
            switch ($t) {
                case 'i':
                    $this->params[] = [$v, \PDO::PARAM_INT];
                    break;
                case 'd':
                    // PDO has no PARAM_FLOAT — bind as string, pgsql will cast
                    $this->params[] = [$v, \PDO::PARAM_STR];
                    break;
                case 'b':
                    $this->params[] = [$v, \PDO::PARAM_LOB];
                    break;
                case 's':
                default:
                    $this->params[] = [$v === null ? null : (string)$v, \PDO::PARAM_STR];
                    break;
            }
        }
        return true;
    }

    public function execute(): bool
    {
        try {
            // Bind each parameter individually so types are honored
            foreach ($this->params as $i => $pair) {
                [$value, $type] = $pair;
                // PDO positional placeholders are 1-based
                $this->stmt->bindValue($i + 1, $value, $type);
            }
            $ok = $this->stmt->execute();
            $this->lastResult = new Result($this->stmt);
            return $ok;
        } catch (\PDOException $e) {
            error_log("[Statement execute] " . $e->getMessage());
            return false;
        }
    }

    public function get_result(): Result
    {
        return $this->lastResult ?? new Result($this->stmt);
    }
}

/**
 * mysqli_result-compatible facade over a PDOStatement.
 */
class Result
{
    private \PDOStatement $stmt;
    public int $num_rows = 0;
    private ?array $rows = null;

    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
        // For SELECTs, fetchAll once so we can expose num_rows like mysqli does.
        try {
            if ($stmt->columnCount() > 0) {
                $this->rows = $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
                $this->num_rows = count($this->rows);
            } else {
                $this->rows = [];
                $this->num_rows = 0;
            }
        } catch (\PDOException $e) {
            $this->rows = [];
            $this->num_rows = 0;
        }
    }

    public function fetch_assoc(): ?array
    {
        if ($this->rows === null || empty($this->rows)) return null;
        return array_shift($this->rows);
    }

    public function free(): void
    {
        $this->rows = null;
    }
}
