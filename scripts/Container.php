<?php

declare(strict_types=1);

require_once __DIR__."/../scripts/DbConnection.php";
require_once __DIR__."/../scripts/Authenticator.php";
require_once __DIR__."/../scripts/Downloader.php";
require_once __DIR__."/../scripts/Uploader.php";


class Container
{
    private array $pool = [];
    private array $serviceMapper = [];
    
    public function __construct()
    {
        $this->serviceMapper = [
            "dbConnection" => $this->getDbConnection(...),
            "authenticator" => $this->getAuthenticator(...),
            "downloader" => $this->getDownloader(...),
            "uploader" => $this->getUploader(...),
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

    public function getDownloader(): Downloader
    {
        // if the service isn't in the pool yet, create it
        if (!isset($this->pool["downloader"])) {
            $this->pool["downloader"] = new Downloader();
        }

        // return the service
        return $this->pool["downloader"];
    }

    public function getUploader(): Uploader
    {
        // if the service isn't in the pool yet, create it
        if (!isset($this->pool["uploader"])) {
            $this->pool["uploader"] = new Uploader();
        }

        // return the service
        return $this->pool["uploader"];
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
