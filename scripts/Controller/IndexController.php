<?php

declare(strict_types=1);

require_once __DIR__ . '/../Client/Response/TemplateResponse.php';


class IndexController extends Controller
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

        $files = $this->fileManipulator->getUploadedFiles();
        
        // if authenticated, continue
        $response = new TemplateResponse('index');
        $response->addData(['uploadedFiles' => $files]);
        return $response;
    }
}
