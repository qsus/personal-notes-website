<?php
header('Content-Type: application/json; charset=utf-8');

$file = $_FILES["file"];
$filename = $_POST["filename"];
$override = $_POST["override"] == "true";

// use file name from form if provided; use only the part after last / to prevent directory traversal attacks
$target = "uploads/" . basename($filename ? $filename : $file["name"]);

// check if file exists
if (!$override && file_exists($target)) {
	echo json_encode(["success" => false, "error" => "File already exists."]);
	die();
}

// save file
if (move_uploaded_file($file["tmp_name"], $target)) {
	echo json_encode(["success" => true]);
} else {
	echo json_encode(["success" => false, "error" => "Could not upload file. move_uploaded_file() failed."]);
}
?>