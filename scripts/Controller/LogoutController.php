<?php

declare(strict_types=1);

class LogoutController extends Controller
{
    public function __construct(
        private Authenticator $authenticator,
    ) {
    }

    public function run(Request $request): Response
    {
        $this->authenticator->logout();
        $response = new TemplateResponse('login');
        return $response;
    }
}
