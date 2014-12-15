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

	if(!isset($angle)){
		$angle = "US- Left";
	}
	echo(json_encode(getMainStories($topic, $angle)));

	//$stories = getMainStories($)
	
	function getMainStories($topic, $angle){

		$theStories1 = array();
		$theTopics = explode(",", $topic);
		for($i=0; $i<count($theTopics); $i++){

			$topic = $theTopics[$i];
			$angleWhich = dbMassData("SELECT * FROM domains WHERE type='$angle' AND rId!=7");
			
			/*
			if($angleWhich!=NULL){

					$domainId= intval($angleWhich[0]['rId']);
					
			}
			else{
				$domainId = 3;
			}
			*/

			$domainId=0;
		
			$theStories = dbMassData("SELECT * FROM articles WHERE sourceId = $domainId AND topic ='$topic' AND image !='' AND sourceId!=7  ORDER BY timestamp DESC");
			if($theStories!=NULL){
				array_push($theStories1, $theStories[0]);
			}
			else{
			$theStories = dbMassData("SELECT * FROM articles WHERE sourceId = 4 AND topic ='$topic' AND image !='' AND sourceId!=7  ORDER BY timestamp DESC");
				if($theStories!=NULL){
					array_push($theStories1, $theStories[0]);
				}
			}

			
		}

		return($theStories1);
	}



?>