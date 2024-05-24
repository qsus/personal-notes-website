<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Client\Response\TemplateResponse;
use App\Helper\Authenticator;
use App\Exception\NotLoggedInException;
use App\Helper\UploadedFile;

class IndexController extends Controller
{
    public function __construct(
        private Authenticator $authenticator,
    ) {
    }

    public function run(Request $request): Response
    {
        // if not authenticated, call login controller
        if (!$this->authenticator->isAuthenticated($request)) {
            throw new NotLoggedInException();
        }

        $files = UploadedFile::getUploadedFiles();
        
        // if authenticated, continue
        $response = new TemplateResponse('index');
        $response->addData(['uploadedFiles' => $files]);
        return $response;
    }
}
