<?php // check if the user is the user he claims to be; if yes, returns, if not, redirects to login.php

declare(strict_types=1);

session_start();

class Authenticator
{
    public function __construct(private dbConnection $dbConnection)
    {
    }

    public function checkCredentials($userName, $password): bool
    {
        // find user in database
        $user = $this->dbConnection->findUser($userName);

        // check if user exists
        if (!$user) return false;

        // verify password
        return password_verify($password, $user['hash']);
    }

    public function isAuthenticated(): bool
    {
        // check if user is already authenticated using custom session variable
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) { // user logged in
            header('Info: auth from session');
            return true; // return inside an included file will pass control back to the calling script
        }

        // check if user gave us username and password
        if (!isset($_POST['user']) || !isset($_POST['pass'])) return false;

        if ($this->checkCredentials($_POST['user'], $_POST['pass'])) {
            $_SESSION['authenticated'] = true; // save login
            session_regenerate_id(); // regenerate session id for security
            return true;
        } else {
            return false;
        }
    }

    public function logout(): void
    {
        session_destroy();
    }
}
