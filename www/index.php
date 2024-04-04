<?php
	require_once __DIR__."/../scripts/authenticate.php";
	require_once __DIR__."/../scripts/fileLoader.php";
	$uploads = uploadsList();
	require __DIR__."/../templates/index.php";
?>