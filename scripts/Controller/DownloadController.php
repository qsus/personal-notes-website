<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Client\Response\FileResponse;
use App\Exception\FileNotFoundException;
use App\Helper\Authenticator;
use App\Exception\NotLoggedInException;
use App\Exception\NotFoundException;
use App\Helper\UploadedFile;

class DownloadController extends Controller
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

        // get file name from query or uri
        $fileName = $request->query('file') ?? array_slice($request->uriSegments(), -1)[0];;

        // create file object
        try {
            $file = new UploadedFile($fileName);
        } catch (FileNotFoundException $e) {
            // if file is not found, throw 404
            throw new NotFoundException();
        }

        $forceDownload = $request->query('download') === 'true';
        $downloadName = $request->query('name') ?? $file->getFileName();

        // create response
        $response = new FileResponse($file, $downloadName, $forceDownload);

        return $response;
    }
}
