<?php
require_once __DIR__."/../scripts/authenticate.php";

// if the request uri starts with /upload, serve the file using download.php
if (strpos($_SERVER['REDIRECT_URL'], "/uploads/") === 0) {
	require __DIR__."/../scripts/download.php";
	exit;
}

require_once __DIR__."/../scripts/fileLoader.php";
$uploads = uploadsList();
require __DIR__."/../templates/index.php";
