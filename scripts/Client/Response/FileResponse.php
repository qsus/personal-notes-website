<?php

declare(strict_types=1);

require_once 'Response.php';

class FileResponse extends Response
{    
    public function __construct(
        private string $fileName,
    ) {
    }


    public function send(): void
    {
        // get file path
        $filePath = __DIR__ . '/../../../uploads/' . $this->fileName;

        // set headers
        header('Content-Type: '.mime_content_type($filePath) ?? 'application/octet-stream');

        // send headers
        //foreach ($this->$headers as $header) {
        //    header($header);
        //}

        //http_response_code($this->statusCode);

        // print data property of Response
        echo $this->data;
        
        // include template file
        include $filePath;
    }
}
