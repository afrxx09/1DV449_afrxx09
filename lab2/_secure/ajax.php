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
  	   	case 'check':
  	   		$r = check();
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
	$created = time();
	
	$q = "INSERT INTO messages (message, name, created) VALUES('$message', '$user', '$created')";
	
	try{
		$stm = $db->prepare($q);
		$r = $stm->execute();
		$id = $db->lastInsertId();
		return array('error' => false, 'id' => $id, 'name' => $user, 'message' => $message, 'created' => $created);
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

function check(){
	$lastId = intval(htmlentities(trim($_POST['lastId'])));
	$count = 0;
	while($count < 30){
		$messages = getLastMessages($lastId);
		if(is_array($messages) && count($messages) > 0){
			return array('error' => false, 'messages' => $messages);
		}
		sleep(1);
		$count++;
	}
	return array('error' => false, 'messages' => array());
	/*
	$lastCheck = isset($_SESSION['lastCheck']) ? $_SESSION['lastCheck'] : null;
	if($lastCheck === null){
		$return = getMessages();
		$lastMessageCreated = $return['messages'][count($return['messages']) - 1]['created'];	
		$_SESSION['lastCheck'] = ($lastMessageCreated !== null ) ? $lastMessageCreated : time();
		return $return;
	}
	$count = 0;
	while($count < 10){
		$messages = getLastMessages();
		if(count($messages) > 0){
			return array('error' => false, 'messages' => $messages);
		}
		sleep(1);
		$count++;
	}
	return array('error' => false, 'messages' => array());
	*/
}

function getLastMessages($lastId){
	$db = null;

	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Del -> " .$e->getMessage());
	}
	
	$q = "SELECT * FROM messages WHERE id > $lastId";
	try {
		$stm = $db->prepare($q);
		$stm->execute();
		return $stm->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {}
	return false;
}