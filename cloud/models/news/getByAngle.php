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
	if(!isset($angles)){
		$angles = "US- Left,US- Right";
	}

	if(!isset($topic)){
		$topic = "obama";
	}
	echo(json_encode(getAngStories($angles, $topic)));

	//$stories = getMainStories($)
	
	function getAngStories($angles, $topic){

		$theStories1 = array();
		$theAngles = explode(",", $angles);
		for($i=0; $i<count($theAngles); $i++){


			$tAngle = $theAngles[$i];

			$sources = dbMassData("SELECT * FROM domains WHERE type = '$tAngle'");
			if($sources!=NULL){
				$domainId= intval($sources[0]['rId']);
				/*
				if($tAngle =="US- Left"){
					$domainId= 4;
				
				}

				*/
				//check Us-Left


				$theStories = dbMassData("SELECT * FROM articles WHERE sourceId  = $domainId AND topic='$topic' AND article !='' AND article !='null' ORDER BY timestamp DESC LIMIT 3");
				if($theStories!=NULL){
					for($j=0; $j<count($theStories); $j++){
						if(strlen($theStories[$j]['article'])>140){
							$theStories[$j]['article']= substr($theStories[$j]['article'], 0, 140);
					
						}
						
					}
					$theStories1[$tAngle]=$theStories;
				}
			}

			if($tAngle=="Twitter"){

				$resp = dbMassData("SELECT * FROM articles WHERE sourceId = 0 AND topic='$topic' ORDER BY timestamp DESC LIMIT 50");
				
				$theStories1[$tAngle]=$resp;
			}
			

			
		}

		return($theStories1);
	}



?>