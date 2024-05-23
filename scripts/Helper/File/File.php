<?php

declare(strict_types=1);

// File implementation should change. File should be generated
// in a factory based on the file name, not the full path.
// Or it can be extended by class UploadedFile, which will
// need just the file name.
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
