<?php
	function uploadsList() {
		$files = scandir('uploads');
		$files = array_diff($files, ['.', '..', '.git-keep']);
		return $files;
	}
