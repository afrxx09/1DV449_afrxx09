<?php

/**
* Just som simple scripts for session handling
*/
function sec_session_start() {
        $session_name = 'sec_session_id'; // Set a custom session name
        $secure = false; // Set to true if using https.
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params(3600, $cookieParams["path"], $cookieParams["domain"], $secure, false);
        $httponly = true; // This stops javascript being able to access the session id.
        session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(); // regenerated the session, delete the old one.
}

function checkUser() {
	if(!session_id()) {
		sec_session_start();
	}

	if(!isset($_SESSION["username"])) {
		header('HTTP/1.1 401 Unauthorized');
		header('location: index.php');
	}

	if(!isset($_SESSION['login_string'])) {
		header('HTTP/1.1 401 Unauthorized');
		header('location: index.php');
	}
	if($_SESSION['login_string'] !==  generateHash()) {
		header('HTTP/1.1 401 Unauthorized');
		header('location: index.php');
	}
	return true;
}

function authUser($u, $p) {
	$db = null;

	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	$q = "SELECT * FROM users WHERE username = '$u' AND password = '$p'";

	$result;
	$stm;
	try {
		$stm = $db->prepare($q);
		$stm->execute();
		$r = $stm->fetch(PDO::FETCH_ASSOC);
		return (!empty($r)) ? $r : null;
	}
	catch(PDOException $e) {}
	return false;
}

function generateHash(){
	return hash('sha512', "123456" + $_SESSION["username"]);
}