<?php

declare(strict_types=1);

class DownloadController extends Controller
{
    public function __construct(
        private Authenticator $authenticator,
        private FileManipulator $fileManipulator,
        private NotFoundController $notFoundController,
        private LoginController $loginController,
    ) {
    }

    public function run(Request $request): Response
    {
        // if not authenticated, call login controller
        if (!$this->authenticator->isAuthenticated($request)) {
            return $this->loginController->run($request);
        }

        // get file name from query or uri
        $fileName = $request->query('file') ?? array_slice($request->uriSegments(), -1)[0];;

        // get file path
        $filePath = __DIR__ . "/../../uploads/$fileName";
        // check if file exists

        if (!file_exists($filePath)) {
            return $this->notFoundController->run($request);
        }

        // create file object
        $file = new File($filePath);

        $forceDownload = $request->query('download') === 'true';
        $downloadName = $request->query('name') ?? $file->getFileName();

        // create response
        $response = new FileResponse($file, $downloadName, $forceDownload);

        return $response;
    }
}
