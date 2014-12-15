<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	

	//ini_set('display_errors',1);  error_reporting(E_ALL); 
	session_start();
	
	if(!isset($_SESSION['userId'])){

		$_SESSION['userId']= generateRandomString1(16);

	}
	$userId = $_SESSION['userId'];

	$webSiteRef = $_SERVER["HTTP_REFERER"];

	$refDomain  = get_domain($webSiteRef);

	$domainI = dbMassData("SELECT * FROM sites WHERE domain = '$refDomain'");
	if($domainI == null){
		dbQuery("INSERT INTO sites (domain) VALUES ('$refDomain')");
		sleep(1);
		$domainI = dbMassData("SELECT * FROM sites WHERE domain = '$refDomain'");
		
	}
	$domainId = intval($domainI[0]['rId']);

	extract($_REQUEST);

	if(!isset($productId)){

		$productId= "exampleProd";
	}
	if(!isset($action)){

		$action= "view";
	}

	/*
	$ratingWeights = dbMassData("SELECT * FROM ratingScheme WHERE domainId=$domainId");

	if($ratingScheme==NULL){

	}
	*/

	if(!isset($rating)){

		$rating = 3;
	}
	$rating = intval($rating);

	dbQuery("INSERT INTO siteActivity (action, urlId, userId,productId, rating, siteUrl) VALUES ('$action', $domainId, '$userId', '$productId', $rating, '$webSiteRef')");

	$resp = array("status"=>"success", "msg"=>"activity added to Alina Cloud");

	if(!isset($_GET['callback'])){

			echo(json_encode($resp));

			return;
	}
	else{
			echo($_GET['callback'] . '(' .json_encode($resp).')');
			return;
	}

	//check for user's session.. if not get an id

	//get url of site

	// extract domain

	//see if domain has a site id, if not create one

	//get the action

	//get the product id between passed url prefix and suffix

	//get all products from the site

	//get all actions from the user

	//send user actions and ratings and all other actions from the site to recommend app via post


function get_domain($url)
{
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return false;
}


function generateRandomString1($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}



?>