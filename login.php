<?php
session_start();
// load credentials
$formUser = $_POST['user'];
$formPass = $_POST['pass'];

// get user's password hash
require_once "dbConnection.php";
$stmt = $pdo->prepare("SELECT `password` FROM user WHERE user = :user");
$stmt->bindParam(':user', $formUser);
$stmt->execute();
$user = $stmt->fetch(); // fetch() returns false if no row is found

// check if user exists
/*if (!$user) { // user not found
	echo "User doesn't exist";
	exit;
}

// verify password
if (!password_verify($formPass, $user['password'])) { // wrong credentials
	echo "Incorrect password";
	exit;
}*/

// check if user exists, verify password
if ($user && password_verify($formPass, $user['password'])) {
	$_SESSION['authenticated'] = true; // save login
	session_regenerate_id(); // regenerate session id
	header('Location: index.php'); // redirect to main page
	print_r($_SESSION);
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<h1>Login</h1>
		<form action="login.php" method="post">
			<label for="user">Username:</label>
			<input type="text" name="user" id="user" required>
			<br>
			<label for="pass">Password:</label>
			<input type="password" name="pass" id="pass" required>
			<br>
			<input type="submit" value="Login">
		</form>
		<p><?= $result ?></p>
	</body>
</html>