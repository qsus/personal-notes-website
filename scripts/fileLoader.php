<?php // provide function to list files in uploads directory
require_once "authenticate.php";
function uploadsList() {
	$files = scandir(__DIR__.'/../uploads');
	$files = array_diff($files, ['.', '..', '.git-keep']);
	return $files;
}