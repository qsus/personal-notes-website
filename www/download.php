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

$file = basename($_GET['file']);
$filename = __DIR__."/../uploads/$file";
$downloadName = $_GET['name'] ?? $file;
$forceDownload = isset($_GET['download']) && $_GET['download'] != 'false';

if (!file_exists($filename)) {
	http_response_code(404);
	exit;
}

header('Content-Disposition: '.($forceDownload ? 'attachment' : 'inline').'; filename="'.$downloadName.'"');
header('Content-Type: '.mime_content_type($filename) ?? 'application/octet-stream');
header('Content-Length: '.filesize($filename));

// return the file and exit
readfile($filename);
exit;