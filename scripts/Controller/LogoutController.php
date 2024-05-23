<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Client\Response\TemplateResponse;
use App\Helper\Authenticator;

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
