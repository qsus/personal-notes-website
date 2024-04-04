<?php
session_start();

// check if user is already authenticated using custom session variable
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) { // user logged in
	return; // return inside an included file will pass control back to the calling script
} else { // not logged in: redirect to login page
	header('Location: login.php');
	exit;
}