<?php

require_once('views/layout_view.php');
require_once('controllers/controller.php');
require_once('controllers/trafic_info_controller.php');

$v = new LayoutView();
$c = new TraficInfoController();
$a = (isset($_GET['a'])) ? htmlentities(trim($_GET['a'])) : 'index';
$r = $c->$a();
$v->render($r);