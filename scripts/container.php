<?php

declare(strict_types=1);

class Container
{
    private array $pool = [];
    private array $serviceMapper = [];
    
    public function __construct()
    {
        $this->serviceMapper = [
            "dbConnection" => $this->getDbConnection(...),
            "authenticator" => $this->getAuthenticator(...),
        ];
    }
    
    public function getDbConnection(): DbConnection
    {
        // if the service isn't in the pool yet, create it
        if (!isset($this->pool["dbConnection"])) {
            $this->pool["dbConnection"] = new DbConnection();
        }

        // return the service
        return $this->pool["dbConnection"];
    }

    public function getAuthenticator(): Authenticator
    {
        // if the service is't in the pool yet, create it
        if (!isset($this->pool["authenticator"])) {
            $this->pool["authenticator"] = new Authenticator($this->getDbConnection());
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
        return isset($this->pool[$name]);
    }
}
