<?php

namespace App\Exception;

use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends \Exception implements NotFoundExceptionInterface
{
    public function __construct(string $serviceName)
    {
        parent::__construct("Service \"$serviceName\" does not exist.");
    }
}
