<?php

require_once("sec.php");
sec_session_start();

$r = array('error' => true);
$f = isset($_POST['action']) ? htmlentities(trim($_POST['action'])) : null;
$v = isset($_POST['vatoken']) ? validateToken($_POST['vatoken']) : null;

if(checkUser() && $f !== null && $v === true){
	session_write_close();
	$r = check();
}

echo(json_encode($r));

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