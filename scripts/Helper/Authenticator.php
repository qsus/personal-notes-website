<?php

declare(strict_types=1);

class Authenticator
{
    public function __construct(
        private DbConnection $dbConnection,
        private Session $session,
    ) {
    }

    private function passwordVerify($password, $hash): bool
    {
        // if the password needs rehashing, rehash it
        // must not be called before verifying the password
        /*if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->dbConnection->updatePassword($hash); // to be implemented
        }*/
        
        return password_verify($password, $hash);
    }

    private function checkCredentials($userName, $password): bool
    {
        // if either username or password is blank or null, return false
        // cannot use just (!userName || !password) because string "0" would be considered false
        if ($userName === null || $password === null || $userName === "" || $password === "") return false;

        // find user in database
        $user = $this->dbConnection->findUser($userName);

        // check if user exists
        if (!$user) return false;

        // verify password
        return $this->passwordVerify($password, $user['hash']);
    }

    public function isAuthenticated($request): bool
    {
        // check if user is logged in
        if ($this->session->get('authenticated')) return true;

        // if not, check credentials
        $authenticated = $this->checkCredentials($request->query('user'), $request->query('pass'));

        // if credentials are correct, store authenticated status in session
        if ($authenticated) {
            $this->session->set('authenticated', true);
            $this->session->regenerateSessionId(); // regenerate session id to prevent session fixation
        }

        return $authenticated;
    }

    public function logout(): void
    {
        $this->session->set('authenticated', false);
    }
}
