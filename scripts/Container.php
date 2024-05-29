<?php

declare(strict_types=1);

namespace App;

use App\App;
use App\Router;
use App\Client\RequestFactory;
use App\Client\Session;
use App\Helper\DbConnection;
use App\Helper\Authenticator;
use App\Helper\UploadedFileManipulator;
use App\Exception\ServiceNotFoundException;

use App\Controller\IndexController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use App\Controller\DownloadController;
use App\Controller\UploadController;
use App\Controller\NotFoundController;
use App\Controller\ControllerRunner;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $pool = [];
    private array $creatorMapper = [];
    
    public function __construct()
    {
        $this->creatorMapper = [
            "App" => $this->createApp(...),
            "Authenticator" => $this->createAuthenticator(...),
            "ControllerRunner" => $this->createControllerRunner(...),
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
            "UploadedFileManipulator" => $this->createUploadedFileManipulator(...),
        ];
    }

    private function createUploadedFileManipulator(): void
    {
        $this->pool["UploadedFileManipulator"] = new UploadedFileManipulator();
    }
    
    private function createIndexController(): void
    {
        $this->pool["IndexController"] = new IndexController(
            $this->get("Authenticator"),
            $this->get("UploadedFileManipulator"),
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
            $this->get("UploadedFileManipulator"),
        );
    }

    private function createUploadController(): void
    {
        $this->pool["UploadController"] = new UploadController(
            $this->get("Authenticator"),
            $this->get("UploadedFileManipulator"),
        );
    }

    private function createNotFoundController(): void
    {
        $this->pool["NotFoundController"] = new NotFoundController();
    }

    private function createApp(): void
    {
        $this->pool["App"] = new App(
            $this->get("RequestFactory"),
            $this->get("Router"),
            $this->get("ControllerRunner"),
        );
    }

    private function createAuthenticator(): void
    {
        $this->pool["Authenticator"] = new Authenticator(
            $this->get("DbConnection"),
            $this->get("Session"),
        );
    }

    private function createControllerRunner(): void
    {
        $this->pool["ControllerRunner"] = new ControllerRunner(
            // functions that return controllers
            [
                "IndexController" => fn() => $this->get("IndexController"),
                "LoginController" => fn() => $this->get("LoginController"),
                "LogoutController" => fn() => $this->get("LogoutController"),
                "DownloadController" => fn() => $this->get("DownloadController"),
                "UploadController" => fn() => $this->get("UploadController"),
                "NotFoundController" => fn() => $this->get("NotFoundController"),
            ],
        );
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

    public function get(string $id): mixed
    {
        // throw error if the service is unknown
        if (!isset($this->creatorMapper[$id])) {
            throw new ServiceNotFoundException($id);
        }

        // create the service if it isn't in the pool yet
        if (!isset($this->pool[$id])) {
            $this->creatorMapper[$id]();
        }

        // always return the service from the pool
        return $this->pool[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->creatorMapper[$id]);
    }
}
