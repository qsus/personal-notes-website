<?php
	require_once "authenticate.php";
	require_once "fileLoader.php";
	$uploads = uploadsList();
	require "template.php";
?>