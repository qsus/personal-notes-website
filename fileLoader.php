<?php
	function uploadsList() {
		$files = scandir("uploads");
		$files = array_diff($files, array(".", "..", ".git-keep"));
		return $files;
	}
?>