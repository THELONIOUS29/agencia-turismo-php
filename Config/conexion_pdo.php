<?php

require_once __DIR__ . '/env.php';

if (!function_exists('agencia_pdo')) {
    function agencia_pdo()
    {
        static $pdo = null;

        if ($pdo instanceof PDO) {
            return $pdo;
        }

        $driver = strtolower(agencia_env('AGENCIA_DB_DRIVER', 'mysql'));
        $host = agencia_env('AGENCIA_DB_HOST', 'localhost');
        $port = agencia_env('AGENCIA_DB_PORT', $driver === 'pgsql' ? '5432' : '3306');
        $database = agencia_env('AGENCIA_DB_NAME', $driver === 'pgsql' ? 'postgres' : 'agencia');
        $username = agencia_env('AGENCIA_DB_USER', $driver === 'pgsql' ? 'postgres' : 'root');
        $password = agencia_env('AGENCIA_DB_PASSWORD', '');

        if ($driver === 'pgsql' || $driver === 'postgres' || $driver === 'postgresql') {
            $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s;sslmode=require', $host, $port, $database);
        } else {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);
        }

        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        if ($driver === 'pgsql' || $driver === 'postgres' || $driver === 'postgresql') {
            $schema = agencia_env('AGENCIA_DB_SCHEMA', 'public');

            if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $schema)) {
                throw new InvalidArgumentException('Nombre de schema invalido.');
            }

            $pdo->exec('SET search_path TO "' . str_replace('"', '""', $schema) . '", public');
        }

        return $pdo;
    }
}

$pdo = agencia_pdo();
$databaseDriver = strtolower(agencia_env('AGENCIA_DB_DRIVER', 'mysql'));
