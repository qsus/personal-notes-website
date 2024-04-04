<?php // check if the user is the user he claims to be; if yes, returns, if not, redirects to login.php
session_start();

// check if user is already authenticated using custom session variable
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) { // user logged in
	header('Info: auth from session');
	return; // return inside an included file will pass control back to the calling script
}

// load credentials
$formUser = $_POST['user'];
$formPass = $_POST['pass'];

// check if user gave us username and password
if (!$formUser || !$formPass) {
	header('Info: no user or pass');
	header('Location: login.php');
	exit;
}

// get user's password hash
require_once __DIR__."/dbConnection.php";
$stmt = $pdo->prepare("SELECT `password` FROM `user` WHERE `user` = :user");
$stmt->bindParam(':user', $formUser);
$stmt->execute();
$user = $stmt->fetch(); // fetch() returns false if no row is found

// check if user exists
if (!$user) { // user not found
	header('Info: username not found');
	header('Location: login.php');
	exit;
}

// verify password
if (password_verify($formPass, $user['password'])) { // correct credentials
	header('Info: auth success, session set');
	$_SESSION['authenticated'] = true; // save login
	session_regenerate_id(); // regenerate session id
	return;
} else { // wrong credentials
	header('Info: wrong password');
	header('Location: login.php');
	exit;
}