<?php

require_once __DIR__ . '/Config/security_headers.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rawurldecode($path);

function agencia_router_path_candidates($path)
{
    $relativePath = ltrim(str_replace('/', DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    $paths = [];

    if ($path === '/' || $path === '') {
        $paths[] = __DIR__;
    }

    if (stripos($path, '/Agencia') === 0) {
        $paths[] = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR . $relativePath;
    }

    $paths[] = __DIR__ . DIRECTORY_SEPARATOR . $relativePath;

    foreach ($paths as $candidate) {
        $real = realpath($candidate);

        if ($real !== false) {
            return $real;
        }
    }

    return false;
}

function agencia_router_require_php($file)
{
    $previousDirectory = getcwd();
    chdir(dirname($file));

    try {
        require $file;
    } finally {
        chdir($previousDirectory);
    }
}

$appRoot = realpath(__DIR__);
$parentRoot = realpath(__DIR__ . '/..');
$file = agencia_router_path_candidates($path);

if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nDisallow:\n";
    return true;
}

if ($file !== false && (strpos($file, $appRoot) === 0 || strpos($file, $parentRoot) === 0) && is_dir($file)) {
    $index = realpath($file . DIRECTORY_SEPARATOR . 'index.php');

    if ($index !== false && (strpos($index, $appRoot) === 0 || strpos($index, $parentRoot) === 0) && is_file($index)) {
        agencia_router_require_php($index);
        return true;
    }

    $index = realpath($file . DIRECTORY_SEPARATOR . 'index.html');

    if ($index !== false && (strpos($index, $appRoot) === 0 || strpos($index, $parentRoot) === 0) && is_file($index)) {
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Length: ' . filesize($index));
        readfile($index);
        return true;
    }
}

if ($file !== false && (strpos($file, $appRoot) === 0 || strpos($file, $parentRoot) === 0) && is_file($file)) {
    if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'php') {
        agencia_router_require_php($file);
        return true;
    }

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $types = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        'html' => 'text/html; charset=utf-8',
        'txt' => 'text/plain; charset=utf-8',
        'pdf' => 'application/pdf',
        'zip' => 'application/zip',
    ];

    if (isset($types[$extension])) {
        header('Content-Type: ' . $types[$extension]);
    }

    header('Content-Length: ' . filesize($file));
    readfile($file);
    return true;
}

http_response_code(404);
header('Content-Type: text/html; charset=utf-8');
echo '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>404 Not Found</title></head><body><h1>404 Not Found</h1><p>Recurso no encontrado.</p></body></html>';
return true;
