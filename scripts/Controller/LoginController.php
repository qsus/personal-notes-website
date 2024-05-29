<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Client\Response\TemplateResponse;
use App\Client\Response\RedirectResponse;
use App\Helper\Authenticator;

class LoginController extends Controller
{
    public function __construct(
        private Authenticator $authenticator
    ) {
    }

    public function run(Request $request): Response
    {
        // if already logged in, redirect to index
        if ($this->authenticator->isAuthenticated($request)) {
            return new RedirectResponse('/', 302);
        }
        // try to log in
        if ($this->authenticator->tryLogin($request->query('user'), $request->query('pass'))) {
            // if successful, redirect to index
            return new RedirectResponse('/', 302);
        } else {
            // if not successful, show login page
            return new TemplateResponse('login');
        }
    }
}
