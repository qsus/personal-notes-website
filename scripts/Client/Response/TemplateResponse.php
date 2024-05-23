<?php

declare(strict_types=1);

namespace App\Client\Response;

use App\Client\Response\Response;

class TemplateResponse extends Response
{    
    public function __construct(
        private string $templateName,
    ) {
    }


    public function send(): void
    {
        // set headers
        $this->setHeader('Content-Type: text/html');
        
        // send headers
        $this->sendHeaders();

        // make data available to the included template
        extract($this->data);

        // include template file
        include __DIR__ . '/../../../templates/' . $this->templateName . '.php';
    }
}
