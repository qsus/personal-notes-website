<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;

class ControllerAccessor
{
    public function __construct(
        private array $controllerGetters,
    ) {
    }

    public function getControlerByName(string $controllerName): Controller
    {
        return $this->controllerGetters[$controllerName]();
    }
}
