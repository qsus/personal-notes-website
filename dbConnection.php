<?php

try {
	// connect to database (and set error mode)
	$pdo = new PDO("mysql:host=localhost;dbname=notes", 'notes', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
	// error message
	echo "Connecting to internal database failed.";
	exit;
}