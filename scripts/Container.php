<?php

declare(strict_types=1);

namespace App;

use App\{
    App,
    Router,
    Helper\DbConnection,
    Helper\Authenticator,
    Client\RequestFactory,
    Client\Session,
    Exception\ServiceNotFoundException,
};
use App\Controller\{
    IndexController,
    LoginController,
    LogoutController,
    DownloadController,
    UploadController,
    NotFoundController,
    ControllerRunner,
};
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
        ];
    }
    
    private function createIndexController(): void
    {
        $this->pool["IndexController"] = new IndexController(
            $this->get("Authenticator"),
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
        );
    }

    private function createUploadController(): void
    {
        $this->pool["UploadController"] = new UploadController(
            $this->get("Authenticator"),
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
                "FaviconController" => fn() => $this->get("FaviconController"),
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

    public function get(string $name): mixed
    {
        // throw error if the service is unknown
        if (!isset($this->creatorMapper[$name])) {
            throw new ServiceNotFoundException($name);
        }

        // create the service if it isn't in the pool yet
        if (!isset($this->pool[$name])) {
            $this->creatorMapper[$name]();
        }

        // always return the service from the pool
        return $this->pool[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->creatorMapper[$name]);
    }
}
