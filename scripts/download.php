<?php // take query and download file; optionally force download and change name

declare(strict_types=1);

// Content Type and Content Disposition
// https://stackoverflow.com/questions/20508788/do-i-need-content-type-application-octet-stream-for-file-download#20509354

//require_once __DIR__."/../scripts/fileLoader.php";
//$uploads = uploadsList();

if (isset($_GET['file'])) { // if the file is passed as a query parameter
    http_response_code(400);
    $fileName = basename($_GET['file']);
    exit;
} else if (isset($_SERVER['REDIRECT_URL'])) { // if the file is passed as a path
    $fileName = basename($_SERVER['REDIRECT_URL']); // this should be possible to improve
} else {
    http_response_code(400);
    exit;
}

$filePath = __DIR__."/../uploads/$fileName";
$downloadName = $_GET['name'] ?? $fileName;
$forceDownload = isset($_GET['download']) && $_GET['download'] != 'false';

if (!file_exists($filePath)) {
    http_response_code(404);
    exit;
}

if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == md5_file($filePath)) {
    http_response_code(304);
    exit;
}

header('Content-Type: '.mime_content_type($filePath) ?? 'application/octet-stream');
// send the file, using X-Sendfile if available
if (getenv('MOD_X_SENDFILE_ENABLED')) {
    header('X-Sendfile: '.$filePath);
    // some headers will get overriden by the X-Sendfile module
    exit;
} else {
    // send the file manually
    header('Content-Disposition: '.($forceDownload ? 'attachment' : 'inline').'; filename="'.$downloadName.'"');
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
