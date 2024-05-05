<?php
// get target from REQUEST_URI
$target = explode("?", $_SERVER['REQUEST_URI'])[0];
//$target = explode("/", $target)[1]; // remove leading slash

// switch statement could be used, but it couldn't handle the /uploads/ folder
// logout.php
if (strpos($target, "/logout.php") === 0) {
	require __DIR__."/../scripts/logout.php";
	exit;
}

// files from /uploads/
if (strpos($target, "/uploads/") === 0) {
	require_once __DIR__."/../scripts/authenticate.php";
	require __DIR__."/../scripts/download.php";
	exit;
}

// upload.php
if (strpos($target, "/upload.php") === 0) {
	require_once __DIR__."/../scripts/authenticate.php";
	require __DIR__."/../scripts/upload.php";
	exit;
}

// index.php
if (strpos($target, "/index.php") === 0) {
	// not requiring authentication
	// redirect to /
	http_response_code(301);
	header("Location: /");
	exit;
}

// /
if ($target === "/") {
	require_once __DIR__."/../scripts/authenticate.php";
	require __DIR__."/../scripts/fileLoader.php";
	$uploads = uploadsList();
	require __DIR__."/../templates/index.php";
	exit;
}

// default
http_response_code(404);
header("Location: /");
exit;