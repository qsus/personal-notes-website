<?php // take query and download file; optionally force download and change name

declare(strict_types=1);

class Downloader {
    // provide function to download a file and exit
    public function download(string $fileName, string $downloadName, bool $forceDownload): void
    {
        $filePath = __DIR__."/../uploads/$fileName";
        if (!file_exists($filePath)) {
            http_response_code(404);
            exit;
        }

        // don't download the file if it hasn't changed
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == md5_file($filePath)) {
            http_response_code(304);
            exit;
        }

        // begin downloading
        // Content Type and Content Disposition
        // https://stackoverflow.com/questions/20508788/do-i-need-content-type-application-octet-stream-for-file-download#20509354
        header('Content-Type: '.mime_content_type($filePath) ?? 'application/octet-stream');
        header('Content-Disposition: '.($forceDownload ? 'attachment' : 'inline').'; filename="'.$downloadName.'"');
        // send the file, using X-Sendfile if available
        if (getenv('MOD_X_SENDFILE_ENABLED')) {
            header('X-Sendfile: '.$filePath);
            exit;
        } else {
            // send the file manually
            header('Content-Length: '.filesize($filePath));
            header('Accept-Ranges: bytes');
            // allow caching
            header('Cache-Control: public');
            header('Pragma: public');
            // check hash of the cached file
            header('ETag: '.md5_file($filePath));
            // return the file and exit
            readfile($filePath);
            exit;
        }
    }
}
