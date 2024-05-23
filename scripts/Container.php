<?php

declare(strict_types=1);

namespace App;

use App\Controller\IndexController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\DownloadController;
use App\Controller\UploadController;
use App\Controller\NotFoundController;
use App\Controller\FaviconController;
use App\Controller\ControllerAccessor;
use App\App;
use App\Helper\DbConnection;
use App\Helper\Authenticator;
use App\Client\RequestFactory;
use App\Router;
use App\Client\Session;
use App\Helper\File\FileManipulator;

require_once __DIR__."/../scripts/Helper/DbConnection.php";
require_once __DIR__."/../scripts/Helper/Authenticator.php";
require_once __DIR__."/../scripts/Client/RequestFactory.php";
require_once __DIR__."/../scripts/Router.php";
require_once __DIR__."/../scripts/Client/Session.php";
require_once __DIR__."/../scripts/App.php";
require_once __DIR__."/../scripts/Controller/IndexController.php";
require_once __DIR__."/../scripts/Controller/LoginController.php";
require_once __DIR__."/../scripts/Controller/NotFoundController.php";
require_once __DIR__."/../scripts/Controller/LogoutController.php";
require_once __DIR__."/../scripts/Controller/DownloadController.php";
require_once __DIR__."/../scripts/Controller/UploadController.php";
require_once __DIR__."/../scripts/Controller/FaviconController.php";
require_once __DIR__."/../scripts/Controller/ControllerAccessor.php";
require_once __DIR__."/../scripts/Helper/File/FileManipulator.php";

class Container
{
    private array $pool = [];
    private array $creatorMapper = [];
    
    public function __construct()
    {
        $this->creatorMapper = [
            "App" => $this->createApp(...),
            "Authenticator" => $this->createAuthenticator(...),
            "ControllerAccessor" => $this->createControllerAccessor(...),
            "DbConnection" => $this->createDbConnection(...),
            "RequestFactory" => $this->createRequestFactory(...),
            "Router" => $this->createRouter(...),
            "Session" => $this->createSession(...),
            "IndexController" => $this->createIndexController(...),
            "LoginController" => $this->createLoginController(...),
            "LogoutController" => $this->createLogoutController(...),
            "DownloadController" => $this->createDownloadController(...),
            "UploadController" => $this->createUploadController(...),
            "NotFoundController" => $this->createNotFoundController(...),
            "FaviconController" => $this->createFaviconController(...),
            "FileManipulator" => $this->createFileManipulator(...),
        ];
    }
    
    private function createIndexController(): void
    {
        $this->pool["IndexController"] = new IndexController(
            $this->get("Authenticator"),
            $this->get("FileManipulator"),
            $this->get("LoginController"),
        );
    }

    private function createLoginController(): void
    {
        $this->pool["LoginController"] = new LoginController(
            $this->get("Authenticator"),
        );
    }

    private function createLogoutController(): void
    {
        $this->pool["LogoutController"] = new LogoutController(
            $this->get("Authenticator"),
        );
    }

    private function createDownloadController(): void
    {
        $this->pool["DownloadController"] = new DownloadController(
            $this->get("Authenticator"),
            $this->get("FileManipulator"),
            $this->get("NotFoundController"),
            $this->get("LoginController"),
        );
    }

    private function createUploadController(): void
    {
        $this->pool["UploadController"] = new UploadController(
            $this->get("Authenticator"),
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
            $this->get("ControllerAccessor"),
            //$this->get("Controllers"),
            //$this->get("ResponseResolver"),
        );
    }

    private function createAuthenticator(): void
    {
        $this->pool["Authenticator"] = new Authenticator(
            $this->get("DbConnection"),
            $this->get("Session"),
        );
    }

    private function createControllerAccessor(): void
    {
        $this->pool["ControllerAccessor"] = new ControllerAccessor(
            // functions that return controllers
            [
                "IndexController" => fn() => $this->get("IndexController"),
                "LoginController" => fn() => $this->get("LoginController"),
                "LogoutController" => fn() => $this->get("LogoutController"),
                "DownloadController" => fn() => $this->get("DownloadController"),
                "UploadController" => fn() => $this->get("UploadController"),
                "NotFoundController" => fn() => $this->get("NotFoundController"),
                "FaviconController" => fn() => $this->get("FaviconController"),
            ],
        );
    }

    private function createFileManipulator(): void
    {
        $this->pool["FileManipulator"] = new FileManipulator();
    }

    private function createDbConnection(): void
    {
        $this->pool["DbConnection"] = new DbConnection();
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
        $this->pool["Session"] = new Session();
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
