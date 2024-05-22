<?php

declare(strict_types=1);

class UploadController extends Controller
{
    public function __construct(
        private Authenticator $authenticator,
        private FileManipulator $fileManipulator,
    ) {
    }

    public function run(Request $request): Response
    {
        // TODO
    }
}
