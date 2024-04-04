<?php // establish connection to database, otherwise exit

try {
	// connect to database (and set error mode)
	$pdo = new PDO("mysql:host=localhost;dbname=notes", 'notes', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) { // connection failed
	// error message
	echo "Connecting to internal database failed.";
	// log exception
	file_put_contents('log/exception.log', $e->getMessage() . "\n", FILE_APPEND);
	exit;
}