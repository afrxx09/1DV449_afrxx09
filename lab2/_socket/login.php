<?php
require_once("sec.php");

// check tha POST parameters
$u = isset($_POST['username']) ? htmlentities(trim($_POST['username'])) : '';
$p = isset($_POST['password']) ? htmlentities(trim($_POST['password'])) : '';

// Check if user is OK
$user = authUser($u, $p);
if($user !== null) {
	// set the session
	sec_session_start();
	$_SESSION['username'] = $user['username'];
	$_SESSION['login_string'] = generateHash();
	header("Location: mess.php"); 
}
else {
	// To bad
	header('HTTP/1.1 401 Unauthorized');
	header("Location: index.php");
}