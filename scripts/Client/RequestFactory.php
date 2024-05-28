<?php

declare(strict_types=1);

namespace App\Client;

use App\Client\Request;

class RequestFactory
{
    public function create(): Request
    {
        return new Request($_REQUEST, $_SERVER, $_FILES);
    }
}
