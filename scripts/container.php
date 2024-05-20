<?php

declare(strict_types=1);

class Container
{
    private array $pool = [];
    private array $serviceMapper = [];
    
    public function __construct()
    {
        $this->serviceMapper = [
            "pdo" => $this->getPDO(...),
            "PDO"  => $this->getPDO(...),
            "authenticator" => $this->getAuthenticator(...),
        ];
    }
    
    public function getPDO(): PDO
    {
        // if the service is't in the pool yet, create it
        if (!isset($this->pool["pdo"])) {
            $this->pool["pdo"] = new DbConnection();
        }

        // return the service
        return $this->pool["pdo"];
    }

    public function getAuthenticator(): Authenticator
    {
        // if the service is't in the pool yet, create it
        if (!isset($this->pool["authenticator"])) {
            $this->pool["authenticator"] = new Authenticator($this->getPDO());
        }

        // return the service
        return $this->pool["authenticator"];
    }

    public function get(string $name): mixed
    {
        // return the service using a function found in the serviceMapper
        return $this->serviceMapper[$name]() ?? throw new NotFoundExceptionInterface();
    }

    public function has($name)
    {
        // check if service exists in pool
        return isset($this->pool["db"]);
    }
}
