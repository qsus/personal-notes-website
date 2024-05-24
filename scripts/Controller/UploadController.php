<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Helper\Authenticator;
use App\Helper\UploadedFile;
use App\Client\Response\TextResponse;
use App\Exception\NotLoggedInException;
use App\Exception\FileExistsException;
use App\Exception\FileUploadException;

class UploadController extends Controller
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

        if (!isset($request->getFiles()['file'])) {
            $response = new TextResponse('Error: no file recieved.');
            $response->setStatusCode(400);
            return $response;
        }
        $file = $request->getFiles()['file']; // get uploaded file
        $fileName = $request->query('filename') ?? $file['name']; // get file name from query or from uploaded file
        $fileName = basename($fileName); // use only the part after last / to prevent directory traversal attacks
        $override = $request->query('override') == 'true'; // get override flag from query

        try {
            UploadedFile::uploadFile($file, $fileName, $override);
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
