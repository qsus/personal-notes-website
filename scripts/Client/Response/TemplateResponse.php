<?php

declare(strict_types=1);

namespace App\Client\Response;

use App\Client\Response\Response;

class TemplateResponse extends Response
{
    // array of variables to be available in the included template
    private array $data = [];

    public function __construct(
        private string $templateName,
    ) {
        $this->setHeader('Content-Type', 'text/html');
    }

    public function addData(array $data): void
    {
        // add data to the data array
        $this->data = array_merge($this->data, $data);
    }

    protected function sendData(): void
    {
        // make data available to the included template
        extract($this->data);

        // include template file
        include __DIR__ . '/../../../templates/' . $this->templateName . '.php';
    }
}
