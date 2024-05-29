<?php

declare(strict_types=1);

namespace App\Client\Response;

class RedirectResponse extends Response
{
    public function __construct(
        private string $location,
        private int $statusCode = 302,
    ) {
        $this->setHeader('Location', $location);
        $this->setStatusCode($statusCode);
    }

    public function sendData(): void
    {
    }
}
