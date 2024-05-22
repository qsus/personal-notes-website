<?php

declare(strict_types=1);

require_once __DIR__."/../scripts/Helper/DbConnection.php";
require_once __DIR__."/../scripts/Helper/Authenticator.php";
//require_once __DIR__."/../scripts/Downloader.php";
//require_once __DIR__."/../scripts/Uploader.php";
//require_once __DIR__."/../scripts/Client/ResponseResolver.php";
require_once __DIR__."/../scripts/Client/RequestFactory.php";
require_once __DIR__."/../scripts/Router.php";
//require_once __DIR__."/../scripts/Controller/ControllerAccessor.php";
require_once __DIR__."/../scripts/Client/Session.php";
//require_once __DIR__."/../scripts/FileLoader.php";
require_once __DIR__."/../scripts/App.php";
require_once __DIR__."/../scripts/Controller/IndexController.php";
require_once __DIR__."/../scripts/Controller/LoginController.php";
require_once __DIR__."/../scripts/Controller/NotFoundController.php";
require_once __DIR__."/../scripts/Controller/LogoutController.php";
require_once __DIR__."/../scripts/Controller/DownloadController.php";
require_once __DIR__."/../scripts/Controller/UploadController.php";
require_once __DIR__."/../scripts/Controller/FaviconController.php";
require_once __DIR__."/../scripts/Helper/FileManipulator.php";


class Container
{
    private array $pool = [];
    private array $creatorMapper = [];
    
    public function __construct()
    {
        $this->creatorMapper = [
            "App" => $this->createApp(...),
            "authenticator" => $this->createAuthenticator(...),
            //"ControllerAccessor" => $this->createControllerAccessor(...),
            "dbConnection" => $this->createDbConnection(...),
            "downloader" => $this->createDownloader(...),
            "fileLoader" => $this->createFileLoader(...),
            "ResponseResolver" => $this->createResponseResolver(...),
            "RequestFactory" => $this->createRequestFactory(...),
            "Router" => $this->createRouter(...),
            "session" => $this->createSession(...),
            "uploader" => $this->createUploader(...),
            "Controllers" => $this->createControllers(...),
            "FileManipulator" => $this->createFileManipulator(...),
        ];
    }

    private function createControllers(): void
    {
        $this->pool["Controllers"] = [
            "IndexController" => new IndexController(
                $this->get("authenticator"),
            ),
            "LoginController" => new LoginController(
                $this->get("authenticator"),
            ),
            "LogoutController" => new LogoutController(
                $this->get("authenticator"),
            ),
            "DownloadController" => new DownloadController(
                $this->get("authenticator"),
                $this->get("FileManipulator"),
            ),
            "UploadController" => new UploadController(
                $this->get("authenticator"),
                $this->get("FileManipulator"),
            ),
            "NotFoundController" => new NotFoundController(),
            "FaviconController" => new FaviconController(),
        ];
    }
    
    private function createApp(): void
    {
        $this->pool["App"] = new App(
            $this->get("RequestFactory"),
            $this->get("Router"),
            //$this->get("ControllerAccessor"),
            $this->get("Controllers"),
            //$this->get("ResponseResolver"),
        );
    }

    private function createAuthenticator(): void
    {
        $this->pool["authenticator"] = new Authenticator(
            $this->get("dbConnection"),
            $this->get("session"),
        );
    }

    /*private function createControllerAccessor(): void
    {
        $this->pool["ControllerAccessor"] = new ControllerAccessor();
    }*/

    private function createFileManipulator(): void
    {
        $this->pool["FileManipulator"] = new FileManipulator();
    }

    private function createDbConnection(): void
    {
        $this->pool["dbConnection"] = new DbConnection();
    }

    private function createDownloader(): void
    {
        $this->pool["downloader"] = new Downloader();
    }

    private function createFileLoader(): void
    {
        $this->pool["fileLoader"] = new FileLoader();
    }
    
    private function createResponseResolver(): void
    {
        $this->pool["ResponseResolver"] = new ResponseResolver();
    }

    private function createRequestFactory(): void
    {
        $this->pool["RequestFactory"] = new RequestFactory();
    }

    private function createRouter(): void
    {
        $this->pool["Router"] = new Router();
    }

    private function createSession(): void
    {
        $this->pool["session"] = new Session();
    }
    
    private function createUploader(): void
    {
        $this->pool["uploader"] = new Uploader();
    }

    public function get(string $name): mixed
    {
        // throw error if the service is unknown
        if (!isset($this->creatorMapper[$name])) {
            throw new NotFoundExceptionInterface();
        }

        // create the service if it isn't in the pool yet
        if (!isset($this->pool[$name])) {
            $this->creatorMapper[$name]();
        }

        // always return the service from the pool
        return $this->pool[$name];
    }

    public function has($name)
    {
        // check if service exists in pool
        return isset($this->pool[$name]);
    }
}
