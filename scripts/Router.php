<?php

declare(strict_types=1);

namespace App;

require_once 'Controller/Controller.php';

// used in App
class Router
{
    public function resolve(string $uri): string // /a/b/c?d=e&f=g
    {
        // convert uri to array of names between slashes
        $uri = explode('?', $uri)[0];            // /a/b/c
        $uri = trim($uri, '/');                  // a/b/c
        $uri = explode('/', $uri);               // ['a', 'b', 'c']

        // resolve controller name
        if ($uri == ['']) return "IndexController";
        if ($uri == ['logout']) return "LogoutController";
        if ($uri == ['favicon.ico']) return "FaviconController";
        if ($uri[0] == 'uploads') return "DownloadController";
        if ($uri[0] == 'download') return "DownloadController";
        if ($uri[0] == 'upload') return "UploadController";
        
        // unresolvable uri
        return "NotFoundController";
    }
}
