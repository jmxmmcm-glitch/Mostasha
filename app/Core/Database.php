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
            $sslmode = 'prefer';

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
     */
    public static function translateSql(string $sql): string
    {
        $sql = str_replace('`', '"', $sql);

        $sql = preg_replace('/INT\s+AUTO_INCREMENT\s+PRIMARY\s+KEY/i', 'SERIAL PRIMARY KEY', $sql);
        $sql = preg_replace('/INT\s+AUTO_INCREMENT/i', 'SERIAL', $sql);

        $sql = preg_replace_callback(
            '/ENUM\s*\(([^)]+)\)/i',
            function ($m) { return 'VARCHAR(50)'; },
            $sql
        );

        $sql = preg_replace('/\bDATETIME\b/i', 'TIMESTAMP', $sql);
        $sql = preg_replace('/\bTINYINT(\([^)]*\))?/i', 'SMALLINT', $sql);

        $sql = preg_replace('/\sENGINE\s*=\s*\w+/i', '', $sql);
        $sql = preg_replace('/\sDEFAULT\s+CHARSET\s*=\s*\w+/i', '', $sql);
        $sql = preg_replace('/\sCHARACTER\s+SET\s+\w+/i', '', $sql);
        $sql = preg_replace('/\sCOLLATE\s+\w+/i', '', $sql);

        if (preg_match('/^\s*INSERT\s+IGNORE\b/i', $sql)) {
            $sql = preg_replace('/^\s*INSERT\s+IGNORE\b/i', 'INSERT', $sql);
            $sql = rtrim($sql, "; \t\n\r");
            $sql .= ' ON CONFLICT DO NOTHING';
        }

        $sql = preg_replace('/^\s*USE\s+\w+\s*;?\s*/im', '', $sql);
        $sql = preg_replace('/CREATE\s+DATABASE\s+IF\s+NOT\s+EXISTS[^;]*;?/i', '', $sql);

        return $sql;
    }

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
        // NOTE: we intentionally do NOT rewrite `?` into `$1, $2, ...`.
        // PDO understands `?` positional placeholders for pgsql natively
        // and binds them via bindValue(1-based index). Rewriting them
        // breaks bindValue when ATTR_EMULATE_PREPARES is false.
        try {
            $stmt = $this->conn->prepare($sql);
            return new Statement($stmt);
        } catch (\PDOException $e) {
            error_log("[DB prepare] " . $e->getMessage() . " SQL: " . $sql);
            return false;
        }
    }

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

    public function multi_query(string $sql): bool
    {
        $sql = self::translateSql($sql);
        $statements = preg_split('/;\s*(?=(?:[^\']*\'[^\']*\')*[^\']*$)/', $sql);
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if ($stmt === '' || preg_match('/^--/', $stmt)) continue;
            try {
                $this->conn->exec($stmt);
            } catch (\PDOException $e) {
                error_log("[DB multi_query] " . $e->getMessage() . " SQL: " . $stmt);
            }
        }
        return true;
    }
}

/**
 * mysqli_stmt-compatible facade over a PDOStatement.
 *
 * NOTE on bind_param: callers use the spread operator (`...$values`) with
 * a plain array. PHP 8 forbids passing non-references through `...` into
 * a `&...` variadic parameter, so we keep the signature value-only and
 * snapshot the typed values into $params. execute() then re-binds with
 * proper PDO PARAM_* types.
 */
class Statement
{
    private \PDOStatement $stmt;
    private array $params = [];

    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
    }

    public function bind_param(string $types, ...$values): bool
    {
        $this->params = [];
        $count = strlen($types);
        for ($i = 0; $i < $count; $i++) {
            $t = $types[$i];
            $v = $values[$i] ?? null;
            switch ($t) {
                case 'i':
                    $this->params[] = [$v === null ? null : (int)$v, \PDO::PARAM_INT];
                    break;
                case 'd':
                    // PDO has no PARAM_FLOAT — bind as string, pgsql will cast
                    $this->params[] = [$v === null ? null : (string)$v, \PDO::PARAM_STR];
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
            foreach ($this->params as $i => $pair) {
                [$value, $type] = $pair;
                if ($value === null) {
                    $this->stmt->bindValue($i + 1, null, \PDO::PARAM_NULL);
                } else {
                    $this->stmt->bindValue($i + 1, $value, $type);
                }
            }
            return $this->stmt->execute();
        } catch (\PDOException $e) {
            error_log("[Statement execute] " . $e->getMessage());
            return false;
        }
    }

    public function get_result(): Result
    {
        return new Result($this->stmt);
    }
}

/**
 * mysqli_result-compatible facade over a PDOStatement.
 *
 * Fetches all rows once on construction so num_rows is accurate (mysqli
 * semantics) and consecutive fetch_assoc() calls walk the buffered rows.
 */
class Result
{
    private \PDOStatement $stmt;
    public int $num_rows = 0;
    private array $rows = [];
    private int $cursor = 0;

    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
        try {
            if ($stmt->columnCount() > 0) {
                $fetched = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $this->rows = is_array($fetched) ? $fetched : [];
                $this->num_rows = count($this->rows);
            }
        } catch (\PDOException $e) {
            // Statement may already be closed (e.g. exec'd as DML)
            $this->rows = [];
            $this->num_rows = 0;
        }
    }

    public function fetch_assoc(): ?array
    {
        if ($this->cursor >= count($this->rows)) return null;
        return $this->rows[$this->cursor++];
    }

    public function free(): void
    {
        $this->rows = [];
        $this->cursor = 0;
    }
}
