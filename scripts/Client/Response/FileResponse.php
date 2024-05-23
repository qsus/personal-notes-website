<?php

declare(strict_types=1);

require_once 'Response.php';

class FileResponse extends Response
{
    public function __construct(
        private File $file,
        private string $downloadName = '',
        private bool $forceDownload = false,
    ) {
    }

    public function send(): void
    {
        // set headers
        $this->setHeader('Content-Type: ' . $this->file->getMimeType());
        $this->setHeader(
            'Content-Disposition: ' .
            ($this->forceDownload ? 'attachment' : 'inline') . // force download or open in browser
            '; filename="' . $this->downloadName . '"' // download name
        );
        
        // Content Type and Content Disposition explanation:
        // https://stackoverflow.com/questions/20508788/do-i-need-content-type-application-octet-stream-for-file-download#20509354
        // send the file, using X-Sendfile if available
        if (getenv('MOD_X_SENDFILE_ENABLED')) {
            $this->setHeader('X-Sendfile: ' . $this->file->path);
            $this->sendHeaders();
            // do not send the file manually, X-Sendfile will handle it
            return;
        }

        // send the file manually
        $this->setHeader('Content-Length: ' . $this->file->getFileSize());
        $this->setHeader('Accept-Ranges: bytes');
        $this->setHeader('Cache-Control: public');
        $this->setHeader('Pragma: public');
        $this->setHeader('ETag: ' . $this->file->getMD5Hash());
        $this->sendHeaders();
        readfile($this->file->path); // also could call $this->file->readfile()
    }
}
