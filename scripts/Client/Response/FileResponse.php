<?php

declare(strict_types=1);

namespace App\Client\Response;

use App\Exception\FileNotFoundException;
use App\Helper\UploadedFile;

class FileResponse extends Response
{
    public function __construct(
        private UploadedFile $uploadedFile,
        private string $downloadName = '',
        private bool $forceDownload = false,
    ) {
        // explanantion: https://stackoverflow.com/questions/20508788/do-i-need-content-type-application-octet-stream-for-file-download#20509354
        $this->setHeader('Content-Type: ' . $this->uploadedFile->getMimeType());
        $this->setHeader(
            'Content-Disposition: ' .
            ($this->forceDownload ? 'attachment' : 'inline') . // force download or open in browser
            '; filename="' . $this->downloadName . '"' // download name
        );

        // use X-Sendfile if available
        if (getenv('MOD_X_SENDFILE_ENABLED')) {
            $this->setHeader('X-Sendfile: ' . $this->uploadedFile->filePath);
            return;
        } else {
            $this->setHeader('Content-Length: ' . $this->uploadedFile->getFileSize());
            //$this->setHeader('Accept-Ranges: bytes'); // not implemented
            $this->setHeader('Cache-Control: public');
            $this->setHeader('Pragma: public');
            $this->setHeader('ETag: ' . $this->uploadedFile->getMD5Hash());
        }
    }

    protected function sendData(): void
    {
        if (getenv('MOD_X_SENDFILE_ENABLED')) {
            return;
        }
        if (!readfile($this->uploadedFile->filePath)) {
            throw new FileNotFoundException("File $this->uploadedFile->fileName does not exist among uploaded files.");
        }
    }
}
