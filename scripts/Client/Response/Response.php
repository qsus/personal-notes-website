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

    public function setHeader(string $header): void
    {
        // add header to headers array
        $this->headers[] = $header;
    }

    public function setStatusCode(int $statusCode): void
    {
        // set status code
        $this->statusCode = $statusCode;
    }

    abstract protected function sendData(): void;

    //abstract public function sendData(): void;
    // TODO: children should implement sendData method
    // that will allow send() method be defined here
    public function send(): void
    {
        // send status code
        http_response_code($this->statusCode);

        // send headers
        foreach ($this->headers as $header) {
            header($header);
        }

        // send data
        $this->sendData();
    }
}
