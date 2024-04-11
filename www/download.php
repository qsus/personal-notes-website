<?php // take query and download file; optionally force download and change name

// Content Type and Content Disposition
// https://stackoverflow.com/questions/20508788/do-i-need-content-type-application-octet-stream-for-file-download#20509354

require_once __DIR__."/../scripts/authenticate.php";

//require_once __DIR__."/../scripts/fileLoader.php";
//$uploads = uploadsList();

if (!isset($_GET['file'])) {
	http_response_code(400);
	exit;
}

$fileName = basename($_GET['file']);
$filePath = __DIR__."/../uploads/$fileName";
$downloadName = $_GET['name'] ?? $fileName;
$forceDownload = isset($_GET['download']) && $_GET['download'] != 'false';

if (!file_exists($filePath)) {
	http_response_code(404);
	exit;
}

header('Content-Disposition: '.($forceDownload ? 'attachment' : 'inline').'; filename="'.$downloadName.'"');
header('Content-Type: '.mime_content_type($filePath) ?? 'application/octet-stream');
header('Content-Length: '.filesize($filePath));
header('Accept-Ranges: bytes');
// allow caching
header('Cache-Control: public');
header('Pragma: public');
// check hash of the cached file
header('ETag: '.md5_file($filePath));
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == md5_file($filePath)) {
	http_response_code(304);
	exit;
}

// send the file, using X-Sendfile if available
if (function_exists('apache_get_modules') && in_array('mod_xsendfile', apache_get_modules())) {
	header('X-Sendfile: '.$filePath);
	exit;
} else {
	// return the file and exit
	readfile($filePath);
	exit;
}