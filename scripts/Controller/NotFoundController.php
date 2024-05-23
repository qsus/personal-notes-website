<?php

declare(strict_types=1);

class NotFoundController extends Controller
{
    public function __construct(
    ) {
    }

    public function run(Request $request): Response
    {
        $response = new TemplateResponse('404');
        $response->setStatusCode(404);
        return $response;
    }
}
