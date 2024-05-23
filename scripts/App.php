<?php

declare(strict_types=1);

namespace App;

use App\Controller\ControllerAccessor;
use App\Client\RequestFactory;
use App\Router;

class App
{
    public function __construct(
        private RequestFactory $requestFactory, // returns Request instance
        private Router $router, // returns Controller name
        private ControllerAccessor $controllerAccessor, // returns Controller instance
        //private array $controllers,
        //private ResponseResolver $responseResolver, // returns Response instance
    ) {
    }

    public function run(): void
    {
        $request = $this->requestFactory->create();
        $controllerName = $this->router->resolve($request->uri());
        //$controller = $this->controllers[$controllerName];
        $controller = $this->controllerAccessor->getControlerByName($controllerName);
        $response = $controller->run($request);
        $response->send();

        /*try {
            $response = $controller->run($request, $this->responseResolver);
        } catch (Exception $e) {
            $this->logger->log($e, LogLevel::ERROR);
            $response = $this->responseResolver->createTextResponse();
            $response->print('Error 500');
            $response->setCode(ErrorCodes::S_500);
        }
        $this->responseResolver->send($response);*/
    }
}
