<?php

declare(strict_types=1);

require_once __DIR__ . '/Session.php';

class Request
{

    public function query(string $key): string|null
    {
        // return GET or POST value by key; never return empty string
        $result = $_REQUEST[$key] ?? null;
        return $result === '' ? null : $result;
    }

    public function uri(): string
    {
        // return request URI
        return $_SERVER['REQUEST_URI'];
    }

    public function uriSegments(): array
    {
        // return URI segments                           // /a/b/c?d=e&f=g
        $uri = explode('?', $this->uri())[0];            // /a/b/c
        $uri = trim($uri, '/');                          // a/b/c
        return explode('/', $uri);                       // ['a', 'b', 'c']
    }

    public function getFiles(): array
    {
        // return array of uploaded files
        return $_FILES;
    }
}
