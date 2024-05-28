<?php

declare(strict_types=1);

namespace App\Controller;

use App\Client\Request;
use App\Client\Response\Response;

class ControllerRunner
{
    public function __construct(
        private array $controllerGetters,
    ) {
    }

    public function runController(string $controllerName, Request $request): Response
    {
        $controller = $this->controllerGetters[$controllerName]();
        return $controller->run($request);
    }
}
