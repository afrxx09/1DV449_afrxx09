<?php
require_once("sec.php");

if(!session_id()) {
	sec_session_start();
}
session_destroy();
header('Location: index.php');
	