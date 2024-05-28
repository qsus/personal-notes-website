<?php

declare(strict_types=1);

namespace App\Client;

class Request
{
    public function __construct(
        private array $request,
        private array $server,
        private array $files,
    ) {
    }

    public function query(string $key): string|null
    {
        // return GET or POST value by key; never return empty string
        $result = $this->request[$key] ?? null;
        return $result === '' ? null : $result;
    }

    public function uri(): string
    {
        // return request URI
        return $this->server['REQUEST_URI'];
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
        return $this->files;
    }
}
