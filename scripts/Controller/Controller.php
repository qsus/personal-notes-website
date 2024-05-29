<?php

declare(strict_types=1);

namespace App\Controller;

use App\Client\Request;
use App\Client\Response\Response;

abstract class Controller
{
    abstract public function run(Request $request): Response;
}
