<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	

	ini_set('display_errors',1);  error_reporting(E_ALL); 
	session_start();
	$_SESSION['count'] =1;

	$resp = findAndSend($_REQUEST);


	echo(json_encode($resp));

	function findAndSend($emailInfo){

		extract($emailInfo);
		$sleepTime = rand(0,8);
		$sleepTime2= rand(0,10);


		//sleep for a random period of time

		sleep($sleepTime);

		//make sure no more than 2 messages have been sent in the last 1 minute

		$howManyMessagesSent= dbMassData("SELECT * FROM messSent WHERE timestamp >= date_sub(NOW(), interval 1 minute) AND sender = '$sender'");

		if(count($howManyMessagesSent) >= 1){

			return array("status"=>"fail", "msg"=>"2 emails already sent in last hour for this user");
		}



		if(!isset($messageId)){
			return array("status"=>"fail", "msg"=>"please send a messageId");

		}

		//get the id of the message

		//get the message content

		$messageId= intval($messageId);

		$messInfo = dbMassData("SELECT * FROM messages WHERE rId = $messageId");
		$messInfo = $messInfo[0];

		$topics = explode(",", $messInfo['topics']);


		//find out which categories it applies to

		//find out find all emails in that category that have not receieved the messagex

		$whichTopic = count($topics)-1;
		$whichTopic= rand(0,$whichTopic);
		$whichTopThisTime = $topics[$whichTopic];
		echo("topic=". $whichTopThisTime);
		//potential emails

		$potEmails = dbMassData("SELECT * FROM emailLists WHERE topic LIKE '%$whichTopThisTime%' GROUP BY email");

		echo("SELECT * FROM emailLists WHERE topic LIKE '%$whichTopThisTime%'");
		if($potEmails ==null){
			$whichTopic = count($topics)-1;
		$whichTopic= rand(0,$whichTopic);
		$whichTopThisTime = $topics[$whichTopic];
		echo("topic=". $whichTopThisTime);
		//potential emails

		$potEmails = dbMassData("SELECT * FROM emailLists WHERE topic LIKE '%$whichTopThisTime%' GROUP BY email");

		}
		if($potEmails==null){

				return array("status"=>"fail", "msg"=>"no potential emails for the topic". $whichTopThisTime);

		}
		shuffle($potEmails);

		print_r($potEmails);

		//total sent

		$totalSent = 0;
		$emailsSentAlready = dbMassData("SELECT * FROM messSent WHERE messageId= $messageId AND sender='$sender' GROUP BY email");

		echo("SELECT * FROM messSent WHERE messageId= $messageId AND sender='$sender' GROUP BY email");

		$emailsSendingTo =array();
		for($i=0; $i<count($potEmails); $i++){
			$sendingToThisUser=true;
			if($totalSent >=2){

				return array("status"=>"success", "msg"=>"sent to 2 emails in category ". $whichTopThisTime);

			}

			for($j=0; $j<count($emailsSentAlready); $j++){


				if($potEmails[$i]['email'] == $emailsSentAlready[$j]['email']){
					$sendingToThisUser=false;
				}

				if (!filter_var($potEmails[$i]['email'], FILTER_VALIDATE_EMAIL) || strlen($potEmails[$i]['email'])>50) {
    					
    					$sendingToThisUser=false;
						
					}			
				//$totalSent = $totalSent+1;

			}
				if($sendingToThisUser==true){

					array_push($emailsSendingTo, $potEmails[$i]['email']);
			
				}

			}

		

		if(count($emailsSendingTo) <1){

			return array("status"=>"fail", "msg"=>"got no emails this message hasnt been sent to");
		}
		shuffle($emailsSendingTo);

		for($i = 0; $i < count($emailsSendingTo); $i++){

			if($i <1){
				//$emailResp = file_get_contents('http://alan-maybe588.rhcloud.com/cloud/models/email/emailer.php?email='.$emailsSendingTo[$i].'&whichMessHTML='. urlencode($messInfo['message']).'&whichMessRaw='.urlencode($messInfo['message']).'&subject='.urlencode($messInfo['subject']) );
				$emailResp = file_get_contents('http://nodejs-maybe588.rhcloud.com/emailMike/?email='.$emailsSendingTo[$i].'&message='.urlencode($messInfo['message']).'&subject='.urlencode($messInfo['subject']));

				echo($emailResp);

				//allow time between emails
				sleep($sleepTime);

				$goingTo = $emailsSendingTo[$i];
				dbQuery("INSERT INTO messSent (email, sender, messageId) VALUES ('$goingTo', '$sender', $messageId)");

			}

			
		}


		return array("status"=>"success", "msg"=>"sent all the emails");

		

		


		//pick a random 2 email from the list

		//send the message to them
	}





	









?>