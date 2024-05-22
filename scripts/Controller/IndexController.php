<?php

declare(strict_types=1);

require_once __DIR__ . '/../Client/Response/TemplateResponse.php';


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
            $response = new TemplateResponse('login');
            return $response;
        }
        
        // if authenticated, continue
        $response = new TemplateResponse('index');
        $response->addData(['uploads' => ['file1', 'file2']]);
        return $response;
    }
}
