<?php

declare(strict_types=1);

namespace App;

use Exception;
use App\Router;
use App\Client\RequestFactory;
use App\Controller\ControllerRunner;
use App\Exception\FileNotFoundException;
use App\Exception\NotLoggedInException;
use App\Exception\NotFoundException;

class App
{
    public function __construct(
        private RequestFactory $requestFactory, // creates Request instance
        private Router $router, // returns Controller name
        private ControllerRunner $controllerRunner, // returns Response instance
    ) {
    }

    public function run(): void
    {
        $request = $this->requestFactory->create();
        $controllerName = $this->router->resolve($request->uri());
        try {
            $response = $this->controllerRunner->runController($controllerName, $request);
        } catch (NotLoggedInException $e) {
            $response = $this->controllerRunner->runController('LoginController', $request);
        } catch (NotFoundException|FileNotFoundException $e) {
            $response = $this->controllerRunner->runController('NotFoundController', $request);
        } catch (Exception $e) {
            throw $e;
            //$response = $this->controllerRunner->runController('ErrorController', $request);
        }
        $response->send();
    }
}
