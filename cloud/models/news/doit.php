<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	

	ini_set('display_errors',1);  error_reporting(E_ALL); 
	/*

	$command = 'mysql'
        . ' --host=' . 'localhost'
        . ' --user=' . 'admintFHZxvz'
        . ' --password=' . 'B7IFKgFVFp3L'
        . ' --database=' . 'alan'
        . ' --execute="SOURCE ' . $_SERVER['DOCUMENT_ROOT'].'cloud/models/activity/alan.sql';
;
echo('doing');
$output1 = shell_exec($command);
//$output2 = shell_exec($command . '/site_structure.sql"');
echo('did it');
*/
echo('showing tables');
$resp = dbMassData("SHOW TABLES");
print_r($resp);

$sql = file_get_contents("alan.sql");
dbQuery($sql);


?>