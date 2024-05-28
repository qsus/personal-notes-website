<?php

declare(strict_types=1);

namespace App;

class Router
{
    private array $routes = [];

    public function __construct()
    {
        // regex pattern to match uri
        $this->routes = [
            // note that dollar sign ensures that the uri ends with the pattern
            '/^$/' => "IndexController",
            '/^logout$/' => "LogoutController",
            '/^uploads/' => "DownloadController",
            '/^download/' => "DownloadController",
            '/^upload/' => "UploadController",
        ];
    }

    public function resolve(string $uri): string // /a/b/c?d=e&f=g
    {
        // convert uri to array of names between slashes
        $uri = explode('?', $uri)[0];            // /a/b/c
        $uri = trim($uri, '/');                  // a/b/c

        // loop through routes and return controller name if uri matches
        foreach ($this->routes as $pattern => $controllerName) {
            if (preg_match($pattern, $uri)) {
                return $controllerName;
            }
        }
        
        // unresolvable uri
        return "NotFoundController";
    }
}
