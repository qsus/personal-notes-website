<?php

declare(strict_types=1);

require_once __DIR__ . "/Request.php";

class RequestFactory
{
    public function create(): Request
    {
        return new Request();
    }
}
