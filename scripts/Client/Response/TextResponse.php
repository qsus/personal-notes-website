<?php

declare(strict_types=1);

namespace App\Client\Response;

class TextResponse extends Response
{
    public function __construct(
        private string $content,
    ) {
    }

    public function send(): void
    {
        // set headers
        $this->setHeader('Content-Type: text/plain');

        // send headers
        $this->sendHeaders();

        echo $this->content;
    }
}
