<?php

declare(strict_types=1);

require_once 'Response.php';

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

        // send headers
        /*foreach ($this->headers as $header) {
            header($header);
        }

        http_response_code($this->statusCode);*/

        // include template file
        echo $this->content;
    }
}
