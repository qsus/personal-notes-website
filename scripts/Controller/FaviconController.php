<?php

declare(strict_types=1);

require_once __DIR__ . '/../Client/Response/FileResponse.php';

class FaviconController extends Controller
{


    public function __construct(
    ) {
    }

    public function run(Request $request): Response
    {
        $filePath = __DIR__ . '/../../uploads/favicon.ico';
        $file = new File($filePath);
        $response = new FileResponse($file);
        return $response;
    }
}