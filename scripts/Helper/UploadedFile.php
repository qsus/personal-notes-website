<?php

declare(strict_types=1);

namespace App\Helper;

use App\Exception\FileNotFoundException;

class UploadedFile
{
    // constant path to the directory where files are uploaded
    const UPLOADS_PATH = __DIR__ . '/../../uploads/';

    public string $fileName;
    public string $filePath;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->filePath = self::UPLOADS_PATH . $fileName;
        // throw an exception if the file does not exist
        if (!file_exists($this->filePath))
            throw new FileNotFoundException("File $fileName does not exist among uploaded files.");
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
        
    public function getMimeType(): string
    {
        return mime_content_type($this->filePath);
    }

    public function getFileSize(): int // in bytes
    {
        return filesize($this->filePath);
    }

    public function getMD5Hash(): string
    {
        return md5_file($this->filePath);
    }
}
