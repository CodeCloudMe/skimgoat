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
	
	echo(json_encode(getTrending()));

	//$stories = getMainStories($)
	
	function getTrending(){

		$trending = file_get_contents('http://hawttrends.appspot.com/api/terms/');
		$tre = json_decode($trending, true);

		$theTrends = $tre[3];
		$topTrends = array();
		$skipping = array();
		for($i=0; $i<2; $i++){

			$tren = strtolower($theTrends[$i]);

			$whatTrend = dbMassData("SELECT * FROM topics WHERE topic = '$tren' AND active = 'true' ");
			if($whatTrend ==NULL){

				array_push($skipping, $tren);
				//wait
				//getNews($tTop);
				//dbQuery("INSERT INTO topics (topic) VALUES ('$tren')");

			}
			else{
			array_push($topTrends, $tren);
			}


		}

		return array("status"=>"success", "data"=>$topTrends, "skipping"=>$skipping);



	}

















?>