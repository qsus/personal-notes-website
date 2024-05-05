<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
	</head>
	<body>
		<h1>Login</h1>
		<?php if (isset($loginError)): ?>
			<p><?= $loginError ?></p>
		<?php endif; ?>
		<form action="/" method="post">
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