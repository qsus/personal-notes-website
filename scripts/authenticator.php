<?php // check if the user is the user he claims to be; if yes, returns, if not, redirects to login.php

declare(strict_types=1);

session_start();

class Authenticator
{
    public function __construct(private PDO $pdo)
    {
    }

    public function checkCredentials($user, $password): bool
    {
        // get user's password hash
        $stmt = $this->pdo->prepare("SELECT `password` FROM `user` WHERE `user` = :user");
        $stmt->bindParam(':user', $user);
        $stmt->execute();
        $user = $stmt->fetch(); // fetch() returns false if no row is found

        // check if user exists
        if (!$user) { // user not found
            return false;
        }

        // verify password
        if (password_verify($password, $user['password'])) { // correct credentials
            return true;
        } else { // incorrect credentials
            return false;
        }
    }

    public function isAuthenticated(): bool
    {
        // check if user is already authenticated using custom session variable
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) { // user logged in
            header('Info: auth from session');
            return true; // return inside an included file will pass control back to the calling script
        }

        // load credentials
        $formUser = $_POST['user'];
        $formPass = $_POST['pass'];

        // check if user gave us username and password
        if (!$formUser || !$formPass) {
            return false;
        }

        if ($this->checkCredentials($formUser, $formPass)) {
            $_SESSION['authenticated'] = true; // save login
            session_regenerate_id(); // regenerate session id for security
            return true;
        } else {
            return false;
        }
    }
}
