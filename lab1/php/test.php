<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://coursepress.lnu.se/kurser/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'Andreas Fridlund, afrxx09');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($ch);
if($result === false){
	var_dump(curl_error($ch));
	exit;
}
curl_close($ch);
var_dump($result);
exit;