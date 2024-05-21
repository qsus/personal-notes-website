<?php

declare(strict_types=1);

class Uploader
{
    public function upload(array $file, string $filename, bool $override): void
    {
        // use file name from form if provided; use only the part after last / to prevent directory traversal attacks
        $target = __DIR__."/../uploads/".basename($filename);

        // check if file exists
        if (!$override && file_exists($target)) {
            http_response_code(409);
            header('Content-Type: text/plain; charset=utf-8');
            echo "Error: file already exists.";
            exit;
        }

        // save file
        if (move_uploaded_file($file["tmp_name"], $target)) {
            http_response_code(201);
            header("Location: uploads/".urlencode($filename));
            exit;
        } else {
            http_response_code(503);
            header('Content-Type: text/plain; charset=utf-8');
            echo "Error: move_uploaded_file failed.";
            exit;
        }
    }
}
