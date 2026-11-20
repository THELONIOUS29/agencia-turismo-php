<?php

if (!function_exists('agencia_load_env')) {
    function agencia_load_env($path)
    {
        if (!is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim(trim($value), "\"'");

            if ($name === '' || getenv($name) !== false) {
                continue;
            }

            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

if (!function_exists('agencia_env')) {
    function agencia_env($name, $default = null)
    {
        $value = getenv($name);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}

agencia_load_env(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');

require_once __DIR__ . '/security_headers.php';
