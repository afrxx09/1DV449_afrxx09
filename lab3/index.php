<?php

session_start();

ini_set( 'default_charset', 'UTF-8' );

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__FILE__) . DS);
define('ROOT_PATH', '/' . basename(dirname(__FILE__)) . '/');

require_once(ROOT_DIR . 'views' . DS . 'layout_view.php');
require_once(ROOT_DIR . 'controllers' . DS . 'trafic_info_controller.php');

$v = new LayoutView();
$c = new TraficInfoController();
$a = (isset($_GET['a'])) ? htmlentities(trim($_GET['a'])) : 'index';
$r = $c->$a();
$v->render($r);