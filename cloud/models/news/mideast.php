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

	$data = file_get_contents('http://webapps.alarabiya.net/aa-search/api/searchService/search/'.urlencode($topic).'/en/all/0');

	$data = str_replace('callback(', "", $data);

	$data = substr_replace($data, '', -1);
	echo($data);


?>