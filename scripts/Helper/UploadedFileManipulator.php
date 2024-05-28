<?php

declare(strict_types=1);

namespace App\Helper;

use App\Exception\FileExistsException;
use App\Exception\FileUploadException;

class UploadedFileManipulator
{
    // constant path to the directory where files are uploaded
    const UPLOADS_PATH = __DIR__ . '/../../uploads/';

    public static function getUploadedFiles(): array
    {
        // return array of UploadedFiles
        $files = [];
        foreach (scandir(self::UPLOADS_PATH) as $file) {
            // skip directories
            if (is_dir(self::UPLOADS_PATH . $file)) {
                continue;
            }
            // if $file is in ignore array
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $files[] = new UploadedFile($file);
        }
        return $files;
    }

    public static function uploadFile(array $file, string $fileName, bool $allowOverride): void
    {
        // use file name from form if provided; use only the part after last / to prevent directory traversal attacks
        $filePath = self::UPLOADS_PATH . basename($fileName);

        // check if file exists
        if (!$allowOverride && file_exists($filePath))
            throw new FileExistsException("File $fileName already exists.");

        // save file
        if (!move_uploaded_file($file["tmp_name"], $filePath))
            throw new FileUploadException("Uploading file $fileName failed.");
    }
}
