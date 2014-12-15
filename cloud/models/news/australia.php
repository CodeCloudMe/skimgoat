<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	//header('Content-Type: application/json');

	//ini_set('display_errors',1);  error_reporting(E_ALL); 
	/*
	session_start();
	
	if(!isset($_SESSION['userId'])){

		$_SESSION['userId']= generateRandomString1(16);

	}

	*/
	extract($_REQUEST);
	if(!isset($topic)){
		$topic = "obama";
	}

	$data = file_get_contents('http://search.abc.net.au/s/search.json?query='.urlencode($topic).'&collection=abcall_meta&form=simple');

	$data1 = json_decode($data, true);

	$data2=$data1['response']['resultPacket'];

	echo(json_encode($data2));


?>