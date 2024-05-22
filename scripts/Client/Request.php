<?php

declare(strict_types=1);

require_once __DIR__ . '/Session.php';

class Request
{

    public function query(string $key): string
    {
        // return GET or POST value by key
        return $_REQUEST[$key] ?? '';
    }

    public function uri(): string
    {
        // return request URI
        return $_SERVER['REQUEST_URI'];
    }
}
