<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	

	ini_set('display_errors',1);  error_reporting(E_ALL); 
	session_start();
	$_SESSION['count'] =1;

	$resp = crunch();


	echo(json_encode($resp));



	//prop

	function crunch(){

		
		$results = dbMassData("SELECT COUNT(*) FROM words");
			//print_r($results);
		$count = $results[0]['COUNT(*)'];
		$parseLimit=intval($count);

	
	//	$parseLimit = 40000;
		

		for($i=0; $i<$parseLimit; $i++){

		
			$min = $i;
			
			$wordInsts = dbMassData("SELECT * FROM words LIMIT $min, 1");
			$theWord = $wordInsts[0]['word'];

				if (preg_match('/\W/', $theWord)){
   		 			echo("non word skipping");
   		 			continue;
   				 }


   				 $crunchedToday = dbMassData("SELECT * FROM crunchedToday WHERE word= '$theWord' AND timestamp >= now() - INTERVAL 1 DAY");

   				 if($crunchedToday!=NULL){
   				 	echo("already done. Skipping");
   		 			continue;

   				 }
   				 echo("not skipping");
   				 	echo('the word='. $theWord);
			$allInsts = dbMassData("SELECT * FROM words WHERE word='$theWord'");

			$proc1=array();
			$proc2=array();
			$proc3=array();
			$succ1=array();
			$succ2=array();
			$succ3=array();

			$succ1Count = array();
			$succ2Count = array();
			$succ3Count = array();

			$proc1Count = array();
			$proc2Count = array();
			$proc3Count = array();

			$howManyInst = count($allInsts);

			for($j=0; $j<count($allInsts); $j++){


				array_push($proc1, $allInsts[$j]['proceededBy1']);
				array_push($proc2, $allInsts[$j]['proceededBy2']);
				array_push($proc3, $allInsts[$j]['proceededBy3']);

				array_push($succ1, $allInsts[$j]['succeeededBy1']);
				array_push($succ2, $allInsts[$j]['succeeededBy1']);
				array_push($succ3, $allInsts[$j]['succeeededBy1']);


				if(!isset($proc1Count[$allInsts[$j]['proceededBy1']])){
					$proc1Count[$allInsts[$j]['proceededBy1']]=1;

				}
				else{
					$proc1Count[$allInsts[$j]['proceededBy1']]= $proc1Count[$allInsts[$j]['proceededBy1']]+1;


				}


				if(!isset($proc2Count[$allInsts[$j]['proceededBy2']])){
					$proc2Count[$allInsts[$j]['proceededBy2']]=1;

				}
				else{
					$proc2Count[$allInsts[$j]['proceededBy2']]= $proc2Count[$allInsts[$j]['proceededBy2']]+1;


				}


				if(!isset($proc3Count[$allInsts[$j]['proceededBy3']])){
					$proc3Count[$allInsts[$j]['proceededBy3']]=1;

				}
				else{
					$proc3Count[$allInsts[$j]['proceededBy3']]= $proc3Count[$allInsts[$j]['proceededBy3']]+1;


				}






				//succeeeded

				if(!isset($succ1Count[$allInsts[$j]['succeeededBy1']])){
					$succ1Count[$allInsts[$j]['succeeededBy1']]=1;

				}
				else{
					$succ1Count[$allInsts[$j]['succeeededBy1']]= $succ1Count[$allInsts[$j]['succeeededBy1']]+1;


				}


				if(!isset($succ2Count[$allInsts[$j]['succeeededBy2']])){
					$succ2Count[$allInsts[$j]['succeeededBy2']]=1;

				}
				else{
					$succ2Count[$allInsts[$j]['succeeededBy2']]= $succ2Count[$allInsts[$j]['succeeededBy2']]+1;


				}


				if(!isset($succ3Count[$allInsts[$j]['succeeededBy3']])){
					$succ3Count[$allInsts[$j]['succeeededBy3']]=1;

				}
				else{
					$succ3Count[$allInsts[$j]['succeeededBy3']]= $succ3Count[$allInsts[$j]['succeeededBy3']]+1;


				}

			}

			echo("the word=".$theWord);
			echo("proc1...<br>");
			print_r($proc1Count);
			echo("succ1...<br>");
			print_r($succ1Count);

			array_multisort($succ1Count, SORT_DESC, $succ1Count);
			array_multisort($succ2Count, SORT_DESC, $succ2Count);
			array_multisort($succ3Count, SORT_DESC, $succ3Count);


			array_multisort($proc1Count, SORT_DESC, $proc1Count);
			array_multisort($proc2Count, SORT_DESC, $proc2Count);
			array_multisort($proc3Count, SORT_DESC, $proc3Count);

			$succ1Prim = "";
			$succ1Occ = 0;
			$succ2Prim = "";
			$succ2Occ = 0;
			$succ3Prim = "";
			$succ3Occ = 0;

			$proc1Prim = "";
			$proc1Occ = 0;
			$proc2Prim = "";
			$proc2Occ = 0;
			$proc3Prim = "";
			$proc3Occ = 0;

			foreach($succ1Count as $key => $value){
				$succ1Prim = $key;
				$value=intval($value);
				$succ1Occ= $value/$howManyInst;

			}


			foreach($succ2Count as $key => $value1){
				$succ2Prim = $key;
				$value1=intval($value1);
				$succ2Occ= $value1/$howManyInst;

			}

			foreach($succ3Count as $key => $value2){
				$succ3Prim = $key;
				$value2=intval($value2);
				$succ3Occ= $value2/$howManyInst;

			}

			foreach($proc1Count as $key => $value3){
				$proc1Prim = $key;
				$value3=intval($value3);
				$proc1Occ= $value3/$howManyInst;

			}

			foreach($proc2Count as $key => $value4){
				$proc2Prim = $key;
				$value4=intval($value4);
				echo("proc2val=".$value4);
				$proc2Occ= $value4/$howManyInst;
				echo("proc2Prim=".$proc2Prim);
				echo("proc2Occ=".$proc2Occ);

			}
			foreach($proc3Count as $key => $value5){
				$proc3Prim = $key;
				$value5=intval($value5);
				echo("proc3val=".$value5);
				$proc3Occ= $value5/$howManyInst;

			}


			dbQuery("INSERT INTO crunch (word, succeededBy1_1, succeededBy2_1, succeededBy3_1, proceededBy1_1, proceededBy2_1, proceededBy3_1,succ1_1Occ, succ2_1Occ, succ3_1Occ, proc1_1Occ, proc2_1Occ, proc3_1Occ) VALUES ('$theWord', '$succ1Prim', '$succ2Prim', '$succ3Prim', '$proc1Prim', '$proc2Prim', '$proc3Prim',$succ1Occ, $succ2Occ, $succ3Occ, $proc1Occ, $proc2Occ, $proc3Occ)");
			echo("INSERT INTO crunch (word, succeededBy1_1, succeededBy2_1, succeededBy3_1, proceededBy1_1, proceededBy2_1, proceededBy3_1,succ1_1Occ, succ2_1Occ, succ3_1Occ, proc1_1Occ, proc2_1Occ, proc3_1Occ) VALUES ('$theWord', '$succ1Prim', '$succ2Prim', '$succ3Prim', '$proc1Prim', '$proc2Prim', '$proc3Prim',$succ1Occ, $succ2Occ, $succ3Occ, $proc1Occ, $proc2Occ, $proc3Occ)");


			dbQuery("INSERT INTO crunchedToday (word) VALUES ('$theWord')");


		}

	}



?>