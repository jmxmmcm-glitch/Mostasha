<?php

/**
 * Database configuration.
 *
 * On Render we set DATABASE_URL (or individual DB_* env vars).
 * Locally you can also set these via your shell or .env.
 */

return [
    // If present, this single URL takes precedence (Render gives us this).
    'url'      => getenv('DATABASE_URL') ?: null,

    // Fallback individual settings (used when DATABASE_URL is not set):
    'host'     => getenv('DB_HOST')     ?: 'localhost',
    'port'     => getenv('DB_PORT')     ?: 5432,
    'user'     => getenv('DB_USER')     ?: 'postgres',
    'password' => getenv('DB_PASSWORD') ?: '',
    'dbname'   => getenv('DB_NAME')     ?: 'emc_db',
];
