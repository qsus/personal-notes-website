<?php
require_once "authenticate.php";

header('Content-Type: application/json; charset=utf-8');

$file = $_FILES["file"];
$filename = basename($_POST["name"] ?? $file["name"]);
$override = $_POST["override"] == true;

// use file name from form if provided; use only the part after last / to prevent directory traversal attacks
$target = __DIR__."/../uploads/".$filename;

// check if file exists
if (!$override && file_exists($target)) {
	http_response_code(409);
	exit;
}

// save file
if (move_uploaded_file($file["tmp_name"], $target)) {
	http_response_code(201);
	header("Location: download.php?file=".urlencode($filename));
} else {
	http_response_code(500);
}
