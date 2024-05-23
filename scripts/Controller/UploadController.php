<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Helper\Authenticator;
use App\Helper\File\FileManipulator;
use App\Controller\LoginController;
use App\Client\Response\TextResponse;

class UploadController extends Controller
{
    public function __construct(
        private Authenticator $authenticator,
        private FileManipulator $fileManipulator,
        private LoginController $loginController,
    ) {
    }

    public function run(Request $request): Response
    {
        // if not authenticated, call login controller
        if (!$this->authenticator->isAuthenticated($request)) {
            return $this->loginController->run($request);
        }

        $file = $request->getFiles()['file']; // get uploaded file
        $fileName = $request->query('filename') ?? $file['name']; // get file name from query or from uploaded file
        $fileName = basename($fileName); // use only the part after last / to prevent directory traversal attacks
        echo $request->query('filename') === "";
        $override = $request->query('override') == 'true'; // get override flag from query

        // check if file exists
        if (!$override && $this->fileManipulator->fileExists($fileName)) {
            $response = new TextResponse('Error: file already exists.');
            $response->setStatusCode(409);
            return $response;
        }

        // upload file
        if ($this->fileManipulator->uploadFile($file, $fileName)) {
            $response = new TextResponse('File uploaded.');
            $response->setStatusCode(201);
            return $response;
        } else {
            $response = new TextResponse('Error: uploading file failed.');
            $response->setStatusCode(503);
            return $response;
        }
    }
}
