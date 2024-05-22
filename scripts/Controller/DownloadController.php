<?php

declare(strict_types=1);

class DownloadController extends Controller
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
