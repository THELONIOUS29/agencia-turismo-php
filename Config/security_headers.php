<?php

if (!function_exists('agencia_is_https')) {
    function agencia_is_https()
    {
        return (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443) ||
            (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
        );
    }
}

if (!function_exists('agencia_csp_nonce')) {
    function agencia_csp_nonce()
    {
        static $nonce = null;

        if ($nonce === null) {
            try {
                $nonce = base64_encode(random_bytes(16));
            } catch (Exception $exception) {
                $nonce = base64_encode(uniqid('', true));
            }
        }

        return $nonce;
    }
}

if (!function_exists('agencia_csp_hash_sources')) {
    function agencia_csp_hash_sources(array $values)
    {
        $hashes = [];

        foreach (array_unique($values) as $value) {
            $hashes[] = "'sha256-" . base64_encode(hash('sha256', $value, true)) . "'";
        }

        return implode(' ', $hashes);
    }
}

if (!function_exists('agencia_inline_event_hashes')) {
    function agencia_inline_event_hashes()
    {
        return agencia_csp_hash_sources([
            "window.location.href='./vis/Blogs.php'",
            'showMessage()',
            "window.location.href='./vis/Destinos.php'",
            "window.location.href='./vis/Paquetes.php'",
            "window.location.href='../vis/Blog_1.php'",
            "window.location.href='../Vista/Blog_1.php'",
            "window.location.href='../Vista/Blog_2.php'",
            "window.location.href='../Controlador/controlador.php?campo=14'",
            "window.location.href='../Controlador/controlador.php?campo=16'",
            'showOptions()',
            'hideOptions(event)',
            'return validateForm()',
        ]);
    }
}

if (!function_exists('agencia_send_security_headers')) {
    function agencia_send_security_headers()
    {
        if (headers_sent()) {
            return;
        }

        $nonce = agencia_csp_nonce();
        $inlineEventHashes = agencia_inline_event_hashes();

        header_remove('X-Powered-By');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

        header(
            "Content-Security-Policy: " .
            "default-src 'self'; " .
            "base-uri 'self'; " .
            "object-src 'none'; " .
            "frame-ancestors 'self'; " .
            "form-action 'self'; " .
            "img-src 'self' data:; " .
            "font-src 'self' https://fonts.gstatic.com https://fonts.googleapis.com https://unpkg.com; " .
            "style-src 'self' 'nonce-" . $nonce . "' https://fonts.googleapis.com; " .
            "style-src-elem 'self' 'nonce-" . $nonce . "' https://fonts.googleapis.com; " .
            "style-src-attr 'none'; " .
            "script-src 'self' 'nonce-" . $nonce . "' https://unpkg.com https://www.gstatic.com; " .
            "script-src-elem 'self' 'nonce-" . $nonce . "' https://unpkg.com https://www.gstatic.com; " .
            "script-src-attr 'unsafe-hashes' " . $inlineEventHashes . "; " .
            "connect-src 'self' https://api.hunter.io"
        );

        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
    }
}

if (!function_exists('agencia_add_csp_nonces')) {
    function agencia_add_csp_nonces($buffer)
    {
        if (stripos($buffer, '<script') === false && stripos($buffer, '<style') === false) {
            return $buffer;
        }

        $nonce = htmlspecialchars(agencia_csp_nonce(), ENT_QUOTES, 'UTF-8');

        return preg_replace_callback(
            '/<(script|style)\b(?![^>]*\bnonce\s*=)([^>]*)>/i',
            function ($matches) use ($nonce) {
                return '<' . $matches[1] . ' nonce="' . $nonce . '"' . $matches[2] . '>';
            },
            $buffer
        );
    }
}

if (!function_exists('agencia_start_csp_nonce_buffer')) {
    function agencia_start_csp_nonce_buffer()
    {
        if (!defined('AGENCIA_CSP_BUFFER_STARTED')) {
            define('AGENCIA_CSP_BUFFER_STARTED', true);
            ob_start('agencia_add_csp_nonces');
        }
    }
}

agencia_send_security_headers();
agencia_start_csp_nonce_buffer();
