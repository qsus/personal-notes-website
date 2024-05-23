<?php

declare(strict_types=1);

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
