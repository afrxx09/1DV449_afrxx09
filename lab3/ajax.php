<?php

session_start();

ini_set('default_charset', 'UTF-8');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__FILE__) . DS);
define('ROOT_PATH', '/' . basename(dirname(__FILE__)) . '/');
require_once(ROOT_DIR . 'controllers' . DS . 'trafic_info_controller.php');

$c = new TraficInfoController();
$a = (isset($_POST['action'])) ? htmlentities(trim($_POST['action'])) : null;
if($a == null || !method_exists($c, $a)){
	echo json_encode(array('error' => true, 'error-message' => 'No action-method'));
	exit;
}
try{
	$r = $c->$a();
	echo json_encode($r);
}
catch(Exception $e){
	echo json_encode(array('error' => true, 'error-message' => 'No action-method in controller'));
}