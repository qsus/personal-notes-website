<?php

declare(strict_types=1);

class File
{
    
    public function __construct(
        public string $path,
    ) {
    }

    public function getFileName(): string
    {
        return basename($this->path);
    }
        
    public function getMimeType(): string
    {
        return mime_content_type($this->path);
    }

    public function getFileSize(): int // in bytes
    {
        return filesize($this->path);
    }

    public function getMD5Hash(): string
    {
        return md5_file($this->path);
    }
}
