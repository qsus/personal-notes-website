<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<h1>Login</h1>
		<form action="index.php" method="post">
			<label for="user">Username:</label>
			<input type="text" name="user" id="user" required>
			<br>
			<label for="pass">Password:</label>
			<input type="password" name="pass" id="pass" required>
			<br>
			<input type="submit" value="Login">
		</form>
	</body>
</html>