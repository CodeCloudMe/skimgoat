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
	if(!isset($url)){

		$url = 'http://skimgoat.com';
	}

	if(!isset($requester)){

		$requester = 'guest';
	}

	if(!isset($category)){

		$category = 'No Category';
	}

	$resp = shorten($url, $requester);
	echo(json_encode($resp));
	
	function shorten($url, $requester, $category){


		$rand = realRand(5);
		$exists = dbMassData("SELECT * FROM shorts WHERE short = '$rand' ");
		if($exists !=NULL){
			$rand = realRand(7);
		}

		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		$title = getTitle($url);

		dbQuery("INSERT INTO shorts (requester, url, title, short, ip, category) VALUES ('$requester', '$url', '$title', '$rand', '$ip', '$category') ");

		return(array("status"=>"success", "url"=>$rand, "title"=>$title));


	}

	


	function getTitle($Url){
    $str = file_get_contents($Url);
    if(strlen($str)>0){
        preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
        return $title[1];
    	}
	}

	function realRand($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}





?>