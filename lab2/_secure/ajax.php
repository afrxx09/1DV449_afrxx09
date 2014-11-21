<?php
require_once("sec.php");
sec_session_start();

$r = array('error' => true);
$f = isset($_POST['action']) ? htmlentities(trim($_POST['action'])) : null;
$v = isset($_POST['vatoken']) ? validateToken($_POST['vatoken']) : null;

if(checkUser() && $f !== null && $v === true){
    switch($f){
    	case 'add':
    		$r = addMessage();
    		break;
    	case 'getMessages':
    		$r = getMessages();
  	   		break;
    }
}
echo(json_encode($r));

function addMessage(){
	$db = null;
	
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
	
	$message = htmlentities($_POST['message']);
	$user = $_SESSION['username'];
	
	$q = "INSERT INTO messages (message, name) VALUES('$message', '$user')";
	
	try{
		$stm = $db->prepare($q);
		$r = $stm->execute();
		return array('error' => false, 'by' => $user, 'message' => $message);
	}
	catch(PDOException $e) {}
	return array('error' => true);
}

function getMessages(){
	$db = null;

	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	
	$q = "SELECT * FROM messages";
	
	try {
		$stm = $db->prepare($q);
		$stm->execute();
		$messages = $stm->fetchAll(PDO::FETCH_ASSOC);
		return array('error' => false, 'messages' => $messages);
	}
	catch(PDOException $e) {}
	return array('error' => true);
}