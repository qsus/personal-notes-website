<?php
	// load credentials
	$formUser = $_SERVER['PHP_AUTH_USER'];
	$formPass = $_SERVER['PHP_AUTH_PW'];
	if (!isset($formUser)) { // no credentials (first connection)
		header('HTTP/1.0 401 Unauthorized');
		header('WWW-Authenticate: Basic realm="Notes"');
		exit;
	}

	// get user's password hash
	require_once "dbConnection.php";
	$stmt = $pdo->prepare("SELECT `password` FROM user WHERE user = ?");
	$stmt->execute([$formUser]);
	$user = $stmt->fetch(); // fetch() returns false if no row is found

	// check if user exists
	if (!$user) { // user not found
		header('HTTP/1.0 401 Unauthorized');
		header('WWW-Authenticate: Basic realm="Notes"');
		exit;
	}

	// verify password
	if (!password_verify($formPass, $user['password'])) { // wrong credentials
		header('HTTP/1.0 401 Unauthorized');
		header('WWW-Authenticate: Basic realm="Notes"');
		exit;
	}

	// correct credentials
?>