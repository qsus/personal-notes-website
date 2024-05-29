<?php

declare(strict_types=1);

namespace App\Client;

use App\Exception\FileNotUploadedException;

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

    public function urlComponents(): array
    {
        return parse_url($this->uri());
    }
    
    public function pathComponents(): array
    {
        $uriPath = $this->urlComponents()['path'];       // /a/b/c
        $uriPath = trim($uriPath, '/');                  // a/b/c
        return explode('/', $uriPath);                   // ['a', 'b', 'c']
    }

    public function getUploadedFile(): array
    {
        $file = $this->files['file'] ?? throw new FileNotUploadedException();
        $file['name'] = $this->query('filename') ?? $file['name'];
        return $file;
    }

    public function requestedFileName(): string
    {
        // return name of uploaded file
        return $this->query('file') ?? array_slice($this->pathComponents(), -1)[0];
    }
}
