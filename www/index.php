<?php
require_once __DIR__."/../scripts/authenticate.php";

// if the request uri is logout.php, log the user out using logout.php
if (strpos($_SERVER['REDIRECT_URL'], "/logout.php") === 0) {
	require __DIR__."/../scripts/logout.php";
	exit;
}

// if the request uri starts with /uploads/, serve the file using download.php
if (strpos($_SERVER['REDIRECT_URL'], "/uploads/") === 0) {
	require __DIR__."/../scripts/download.php";
	exit;
}

// if the request uri starts with upload.php, handle the upload
if (strpos($_SERVER['REDIRECT_URL'], "/upload.php") === 0) {
	require __DIR__."/../scripts/upload.php";
	exit;
}

require_once __DIR__."/../scripts/fileLoader.php";
$uploads = uploadsList();
require __DIR__."/../templates/index.php";
