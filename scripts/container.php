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
        if (isset($this->pool["pdo"])) {
            // if the service is already in the pool, return it
            return $this->pool["pdo"];
        } else {
            // else create it in the pool and return it
            $this->pool["pdo"] = new DbConnection();
            return $this->pool["pdo"];
        }
    }

    public function getAuthenticator(): Authenticator
    {
        if (isset($this->pool["authenticator"])) {
            // if the service is already in the pool, return it
            return $this->pool["authenticator"];
        } else {
            // else create it in the pool and return it
            $this->pool["authenticator"] = new Authenticator($this->getPDO());
            return $this->pool["authenticator"];
        }
    }

    public function get(string $name): mixed
    {
        // use serviceMapper to find the method of the service to call
        if (isset($this->serviceMapper[$name])) {
            return $this->serviceMapper[$name]();
        } else {
            // throw exception if service doesn't exist in mapper
            throw new NotFoundExceptionInterface();
        }
    }

    public function has($name)
    {
        // check if service exists in pool
        return isset($this->pool["db"]);
    }
}
