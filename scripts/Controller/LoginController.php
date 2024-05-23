<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Client\Request;
use App\Client\Response\Response;
use App\Client\Response\TemplateResponse;

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
