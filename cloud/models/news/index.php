<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	//header('Content-Type: application/json');

	ini_set('display_errors',1);  error_reporting(E_ALL); 
	/*
	session_start();
	
	if(!isset($_SESSION['userId'])){

		$_SESSION['userId']= generateRandomString1(16);

	}

	*/
	
	getNews();
	function getNews(){

		$topics = dbMassData("SELECT * FROM topics");

		for($i=0; $i<count($topics); $i++){

			if($topics[$i]['topic']!= "" && $topics[$i]['topic'] != " "){
				getLatestArticles($topics[$i]['topic']);
			}
		}


	}

	function getLatestArticles($topic){

		echo("getting topic". $topic.'<br><br>');
		$sources = dbMassData("SELECT * FROM domains WHERE active='true'");

		for($i=0; $i<count($sources); $i++){

			$domainId = intval($sources[$i]['rId']);
			$sourceInfo = dbMassData("SELECT * FROM articleParse WHERE sourceId = ".$domainId);

			echo("SELECT * FROM articleParse WHERE sourceId = ".$domainId);

			$sourceInfo = $sourceInfo[0];




			print_r($sourceInfo);


			if($sourceInfo['json']=='true'){

				echo("its JSON!");
						$theStuff = file_get_contents($sources[$i]['searchUrl']. urlencode($topic));
						$resp = json_decode($theStuff,true);

						if($sourceInfo['layers']!=''){
							$resp1 = $resp[$sourceInfo['jsonMain']][$sourceInfo['jsonMain']];
						}
						else{
							$resp1 = $resp[$sourceInfo['jsonMain']];
						}
						

						for($t=0; $t<count($resp1); $t++){
						print_r($resp1);

						$resp2 = array_reverse($resp1);
						$resp1= $resp2;
						$cont = $resp1[$t][$sourceInfo['jsonDesc']];
						$url = $resp1[$t][$sourceInfo['jsonUrl']];
						$image = $resp1[$t][$sourceInfo['jsonImage']];	
						$headline =  $resp1[$t][$sourceInfo['jsonTitle']];
						$snip  = substr($cont, 0, 140);
						$sourceId = $domainId;
						$sourceUrl = $sources[$i]['domainUrl'];

						if($cont!=''){
						dbQuery("INSERT INTO articles (url, snip, headline, topic, sourceId, sourceUrl, article, image) VALUES ('$url', '$snip', '$headline', '$topic', $sourceId, '$sourceUrl', '$cont', '$image')");

					
						echo("INSERT INTO articles (url, snip, headline, topic, sourceId, sourceUrl, article, image) VALUES ('$url', '$snip', '$headline', '$topic', $sourceId, '$sourceUrl', '$cont', '$image')");

						}

					}


						/*

							$headline = stripslashes($headline);
					$cont = stripslashes($cont);
					$snip  = substr($cont, 0, 140);
					$sourceId = $domainId;
					$sourceUrl = $sources[$i]['domainUrl'];
					$url = $thisLink;
						*/



			}


			else{
			$searchRes = file_get_contents($sources[$i]['searchUrl']. urlencode($topic));

			$subject = $searchRes;

			echo('start='.$sourceInfo['searchStartRegex']);
			$pattern = '/'.$sourceInfo['searchStartRegex'].'.*?'.$sourceInfo['searchEndRegex'].'/';
			echo('patterns='.$pattern);
		//	preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);

			$matches = explode($sourceInfo['searchStartRegex'], $subject);
			$allLinks =array();
			for($j=0; $j<count($matches); $j++){

				$linkArr = explode($sourceInfo['searchEndRegex'], $matches[$j]);

				$link = $linkArr[0];

				if($link[0]=="/"){
					$link = $sources[$i]['domainUrl']. $link;
				}
				if($link[0]=="h"){
					array_push($allLinks, $link);
				}
				


			}
			print_r($allLinks);

			$allLinks1 = array_reverse($allLinks);
			$allLinks= $allLinks1;
			for($k=0; $k < count($allLinks); $k++){

				$thisLink = $allLinks[$k];
				$alreadyCovered = dbMassData("SELECT * FROM articles WHERE url = '$thisLink' ");
				if($alreadyCovered == NULL){



					
					$content = file_get_contents($thisLink);

					echo("cut by = ".$sourceInfo['startHeadRegex']);

					$headlineArr= explode($sourceInfo['startHeadRegex'], $content);

					$headline = $headlineArr[1];
					//echo("headline=".$headline);
					$headlineFin = explode($sourceInfo['endHeadRegex'],$headline);

					//echo('head2= '.$headlineFin[0]);
					$headline = $headlineFin[0];

					echo("headline=".$headline);


					//get content

					echo("start with=".$sourceInfo['startReg'] );
					$contArr= explode($sourceInfo['startReg'], $content);

					//print_r($contArr);
					$cont = $contArr[1];
					echo("content0=".$cont);
					$contFin = explode($sourceInfo['endReg'],$cont);

					$cont = $contFin[0];

					echo("content1=".$cont);

					if($sourceInfo['ps']=='true'){

						preg_match_all("/<p>(.*?)p>/", $cont, $matches);
						$fullStr = "";
						//echo("matches0=".$matches[0]);
						//$cont = implode('', $matches[1]);
						for($h=0; $h<count($matches); $h++){

							if(isset($matches[$h])){
								echo("matches set");
								$theStr= implode('', $matches[$h]);
								echo($theStr);
								if(strlen($theStr)>20){
								$fullStr = $fullStr . $theStr;
									}
							}
						}
						$cont1 = str_replace('<p>', '', $fullStr);
						$cont=$cont1;



					}

					echo('blue='.$cont);
					
					$headline = str_replace("'", "",$headline);
					//$cont = stripslashes($cont);
					//$cont = stripslashes ($cont);
					$cont = str_replace("'", "",$cont);
				echo("wtf=".$cont."source=".$sourceUrl);
					$snip  = substr($cont, 0, 140);
					$sourceId = $domainId;
					$sourceUrl = $sources[$i]['domainUrl'];
					$url = $thisLink;
				
					if($cont!=''){
						dbQuery("INSERT INTO articles (url, snip, headline, topic, sourceId, sourceUrl, article) VALUES ('$url', '$snip', '$headline', '$topic', $sourceId, '$sourceUrl', '$cont')");

					
						echo("INSERT INTO articles (url, snip, headline, topic, sourceId, sourceUrl, article) VALUES ('$url', '$snip', '$headline', '$topic', $sourceId, '$sourceUrl', '$cont')");

					}
					
					//save article
				}

				
			}



		}
		}
	}



?>