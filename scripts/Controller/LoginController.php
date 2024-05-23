<?php

declare(strict_types=1);

class LoginController extends Controller
{
    public function __construct(
    ) {
    }

    public function run(Request $request): Response
    {
        $response = new TemplateResponse('login');
        return $response;
    }
}
