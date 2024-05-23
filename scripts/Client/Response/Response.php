<?php

declare(strict_types=1);

abstract class Response
{
    private array $headers = [];
    private int $statusCode = 200;
    protected array $data = [];

    public function addData(array $data): void
    {
        // add data to data array
        $this->data = array_merge($this->data, $data);
    }

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

    protected function sendHeaders(): void
    {
        // set status code
        http_response_code($this->statusCode);

        // set headers
        foreach ($this->headers as $header) {
            header($header);
        }
    }

    abstract public function send(): void;
    /*
    {
        // send headers
        $this->sendHeaders();
    }
    */
}
