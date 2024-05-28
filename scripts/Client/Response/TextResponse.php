<?php

declare(strict_types=1);

namespace App\Client\Response;

class TextResponse extends Response
{
    public function __construct(
        private string $content,
    ) {
        $this->setHeader('Content-Type', 'text/plain');
    }

    public function sendData(): void
    {
        echo $this->content;
    }
}
