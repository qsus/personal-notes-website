<?php

require_once __DIR__."/../scripts/dbConnection.php";
require_once __DIR__."/../scripts/authenticator.php";

// create container
class Container {
	private array $pool = [];
	private array $serviceMapper = [];
	
	public function __construct() {
		$this->serviceMapper = [
			"pdo" => $this->getPDO(...),
			"PDO"  => $this->getPDO(...),
			"authenticator" => $this->getAuthenticator(...),
		];
	}
	
	public function getPDO(): PDO {
		if (isset($this->pool["pdo"])) {
			// if the service is already in the pool, return it
			return $this->pool["pdo"];
		} else {
			// else create it in the pool and return it
			$this->pool["pdo"] = new DbConnection();
			return $this->pool["pdo"];
		}
	}

	public function getAuthenticator(): Authenticator {
		if (isset($this->pool["authenticator"])) {
			// if the service is already in the pool, return it
			return $this->pool["authenticator"];
		} else {
			// else create it in the pool and return it
			$this->pool["authenticator"] = new Authenticator($this->getPDO());
			return $this->pool["authenticator"];
		}
	}

	public function get(string $name): mixed {
		// use serviceMapper to find the method of the service to call
		if (isset($this->serviceMapper[$name])) {
			return $this->serviceMapper[$name]();
		} else {
			// throw exception if service doesn't exist in mapper
			throw new NotFoundExceptionInterface();
		}
	}

	public function has($name) {
		// check if service exists in pool
		return isset($this->pool["db"]);
	}
}

// requestLogin function
function requestLogin() {
	http_response_code(401); // unauthorized
	require __DIR__."/../templates/login.php";
	exit;
}

$container = new Container();
$authenticator = $container->get("authenticator");

header("X-Authenticated: ".($authenticator)->isAuthenticated() ? "true" : "false");

// get target from REQUEST_URI
$target = explode("?", $_SERVER['REQUEST_URI'])[0];
//$target = explode("/", $target)[1]; // remove leading slash

// switch statement could be used, but it couldn't handle the /uploads/ folder
// logout.php
if (strpos($target, "/logout.php") === 0) {
	require __DIR__."/../scripts/logout.php";
	exit;
}

// files from /uploads/
if (strpos($target, "/uploads/") === 0) {
	if (!$authenticator->isAuthenticated()) requestLogin();
	require __DIR__."/../scripts/download.php";
	exit;
}

// upload.php
if (strpos($target, "/upload.php") === 0) {
	if (!$authenticator->isAuthenticated()) requestLogin();
	require __DIR__."/../scripts/upload.php";
	exit;
}

// index.php
if (strpos($target, "/index.php") === 0) {
	// not requiring authentication
	// redirect to /
	http_response_code(301);
	header("Location: /");
	exit;
}

// favicon.ico
if (strpos($target, "/favicon.ico") === 0) {
	// not requiring authentication
	http_response_code(200);
	exit;
}

// /
if ($target === "/") {
	if (!$authenticator->isAuthenticated()) requestLogin();
	require __DIR__."/../scripts/fileLoader.php";
	$uploads = uploadsList();
	require __DIR__."/../templates/index.php";
	exit;
}

// default
http_response_code(404);
header("Location: /");
exit;