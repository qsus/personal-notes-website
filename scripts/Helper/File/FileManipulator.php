<?php

declare(strict_types=1);

namespace App\Helper\File;

use App\Helper\File\File;

class FileManipulator
{
    // constant path to uploads directory
    private string $uploadsPath = __DIR__ . '/../../../uploads/';

    public function getUploadedFiles(): array
    {
        // return array of Files
        $files = [];
        foreach (scandir($this->uploadsPath) as $file) {
            // skip directories
            if (is_dir($this->uploadsPath . $file)) {
                continue;
            }
            // if $file is in ignore array
            if (in_array($file, ['.', '..', '.git-keep'])) {
                continue;
            }
            $files[] = new File($file);
        }
        return $files;
    }

    public function fileExists(string $fileName): bool
    {
        return file_exists($this->uploadsPath . $fileName);
    }

    // upload file, override if exists
    // $file format: https://www.php.net/manual/en/features.file-upload.post-method.php
    public function uploadFile(array $file, string $fileName): bool
    {
        // use file name from form if provided; use only the part after last / to prevent directory traversal attacks
        $target = $this->uploadsPath . basename($fileName);

        // save file
        return move_uploaded_file($file["tmp_name"], $target);
    }
}
