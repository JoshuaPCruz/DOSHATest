<?php
$recipient = "dev@ffffflux.com,guasp@ffffflux.com,unifinmx@add.nocrm.io, unifincs@gmail.com,fbb@uniclick.mx";
$urlAPI = 'http://Unifingate-env.eba-mcp5tk2m.us-east-2.elasticbeanstalk.com';
$nameAPI = "unifinGate";
$emailAPI = "dev@ffffflux.com.mx"; 
$passwordAPI = "unifinGate2020";

if($_SERVER["HTTP_HOST"] == 'pruebas.unifin.com.mx'){
	$recipient = "dev@ffffflux.com, guasp@ffffflux.com,unifinmx@add.nocrm.io, unifincs@gmail.com,fbb@uniclick.mx";
	$urlAPI = 'https://pruebas.unifin.com.mx/bitacora/public';
	$nameAPI = "uniclick";
	$emailAPI = "bitacora@uniclick.mx";
	$passwordAPI = "biTacoRa6254";
}

if($_SERVER["HTTP_HOST"] == 'ffffflux.com'){
	$recipient = "dev@ffffflux.com, guasp@ffffflux.com,unifinmx@add.nocrm.io, unifincs@gmail.com,fbb@uniclick.mx";
	//$urlAPI = 'http://Unifingate-env.eba-mcp5tk2m.us-east-2.elasticbeanstalk.com';
	$urlAPI = 'https://pruebas.unifin.com.mx/bitacora/public';
	$nameAPI = "unifinGate";
	$emailAPI = "dev@ffffflux.com.mx";
	$passwordAPI = "unifinGate2020";
}
if($_SERVER["HTTP_HOST"] == 'localhost:8888'){
	$recipient = "dev@ffffflux.com, guasp@ffffflux.com,unifinmx@add.nocrm.io, unifincs@gmail.com,fbb@uniclick.mx";
	$urlAPI = 'http://127.0.0.1:8000';
	$nameAPI = "uniclick";
	$emailAPI = "bitacora@uniclick.mx";
	$passwordAPI = "biTacoRa6254";
}

?>