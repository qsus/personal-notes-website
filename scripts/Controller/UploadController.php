<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Helper\Authenticator;
use App\Client\Response\TextResponse;
use App\Exception\NotLoggedInException;
use App\Exception\FileExistsException;
use App\Exception\FileNotUploadedException;
use App\Exception\FileUploadException;
use App\Helper\UploadedFileManipulator;

class UploadController extends Controller
{
    public function __construct(
        private Authenticator $authenticator,
        private UploadedFileManipulator $uploadedFileManipulator,
    ) {
    }

    public function run(Request $request): Response
    {
        // if not authenticated, call login controller
        if (!$this->authenticator->isAuthenticated($request)) {
            throw new NotLoggedInException();
        }

        try {
            $file = $request->getUploadedFile();
        } catch (FileNotUploadedException $e) {
            $response = new TextResponse('Error: no file recieved.');
            $response->setStatusCode(400);
            return $response;
        }

        $fileName = basename($file['name']); // use only the part after last / to prevent directory traversal attacks
        $override = $request->query('override') == 'true'; // get override flag from query

        try {
            $this->uploadedFileManipulator->uploadFile($file, $fileName, $override);
            $response = new TextResponse('File uploaded.');
            $response->setStatusCode(201);
            return $response;
        } catch (FileExistsException $e) {
            $response = new TextResponse('Error: file already exists.');
            $response->setStatusCode(409);
            return $response;
        } catch (FileUploadException $e) {
            $response = new TextResponse('Error: uploading file failed.');
            $response->setStatusCode(503);
            return $response;
        }
    }
}
