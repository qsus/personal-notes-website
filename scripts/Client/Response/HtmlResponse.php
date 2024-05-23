<?php

declare(strict_types=1);

require_once 'Response.php';

// unused at the moment
class HtmlResponse extends Response
{
    public function __construct(
        private string $content,
    ) {
    }

    public function send(): void
    {
        // set headers
        $this->setHeader('Content-Type: text/html');

        // include template file
        echo $this->content;
    }
}
