<?php

declare(strict_types=1);

namespace App\Client\Response;

abstract class Response
{
    // all responses have the same headers and status code interface,
    // but implement sendData method differently - they may use
    // constuctor or public method to set data

    private array $headers = [];
    private int $statusCode = 200;

    public function setHeader(string $key, string|int $value): void
    {
        // set header
        $this->headers[$key] = $value;
    }

    public function setStatusCode(int $statusCode): void
    {
        // set status code
        $this->statusCode = $statusCode;
    }

    abstract protected function sendData(): void;

    public function send(): void
    {
        // send status code
        http_response_code($this->statusCode);

        // send headers
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        // send data
        $this->sendData();
    }
}
