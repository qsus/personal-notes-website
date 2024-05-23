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
require_once __DIR__."/../scripts/Helper/File/FileManipulator.php";


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
            "RequestFactory" => $this->createRequestFactory(...),
            "Router" => $this->createRouter(...),
            "session" => $this->createSession(...),
            "uploader" => $this->createUploader(...),
            "IndexController" => $this->createIndexController(...),
            "LoginController" => $this->createLoginController(...),
            "LogoutController" => $this->createLogoutController(...),
            "DownloadController" => $this->createDownloadController(...),
            "UploadController" => $this->createUploadController(...),
            "NotFoundController" => $this->createNotFoundController(...),
            "FaviconController" => $this->createFaviconController(...),
            "FileManipulator" => $this->createFileManipulator(...),
            "Controllers" => $this->createControllers(...),
        ];
    }

    private function createControllers(): array
    {
        return $this->pool["Controllers"] = [
            "IndexController" => $this->get("IndexController"),
            "LoginController" => $this->get("LoginController"),
            "LogoutController" => $this->get("LogoutController"),
            "DownloadController" => $this->get("DownloadController"),
            "UploadController" => $this->get("UploadController"),
            "NotFoundController" => $this->get("NotFoundController"),
            "FaviconController" => $this->get("FaviconController"),
        ];
    }
    
    private function createIndexController(): void
    {
        $this->pool["IndexController"] = new IndexController(
            $this->get("authenticator"),
            $this->get("FileManipulator"),
            $this->get("LoginController"),
        );
    }

    private function createLoginController(): void
    {
        $this->pool["LoginController"] = new LoginController(
            $this->get("authenticator"),
        );
    }

    private function createLogoutController(): void
    {
        $this->pool["LogoutController"] = new LogoutController(
            $this->get("authenticator"),
        );
    }

    private function createDownloadController(): void
    {
        $this->pool["DownloadController"] = new DownloadController(
            $this->get("authenticator"),
            $this->get("FileManipulator"),
            $this->get("NotFoundController"),
            $this->get("LoginController"),
        );
    }

    private function createUploadController(): void
    {
        $this->pool["UploadController"] = new UploadController(
            $this->get("authenticator"),
            $this->get("FileManipulator"),
            $this->get("LoginController"),
        );
    }

    private function createNotFoundController(): void
    {
        $this->pool["NotFoundController"] = new NotFoundController();
    }

    private function createFaviconController(): void
    {
        $this->pool["FaviconController"] = new FaviconController();
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
            //throw new NotFoundExceptionInterface();
            throw new Exception("Service not found: $name");
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
