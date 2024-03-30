<?php
	if (!isset($_SERVER['PHP_AUTH_USER'])) { // no credentials (first connection)
		header('HTTP/1.0 401 Unauthorized');
		header('WWW-Authenticate: Basic realm="Notes"');
		exit;
	}

	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	
	if ($user != "admin" || $pass != "admin") { // wrong credentials
		header('HTTP/1.0 401 Unauthorized');
		header('WWW-Authenticate: Basic realm="Notes"');
		exit;
	}
	
	// correct credentials
?>