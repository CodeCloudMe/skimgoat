<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	

	ini_set('display_errors',1);  error_reporting(E_ALL); 
	session_start();
	$_SESSION['count'] =1;

	$resp = addInfo($_REQUEST);


	echo(json_encode($resp));

	function addInfo($emailInfo){


		$email = $emailInfo['email'];
		$topic = $emailInfo['topic'];

		dbQuery("INSERT INTO emailLists (email, topic) VALUES ('$email', '$topic')");
		return true;
	}





	









?>