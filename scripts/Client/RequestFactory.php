<?php

declare(strict_types=1);

namespace App\Client;

use App\Client\Request;

require_once __DIR__ . "/Request.php";

class RequestFactory
{
    public function create(): Request
    {
        return new Request();
    }
}
