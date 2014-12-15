<?php

extract($_REQUEST);

if(!isset($link)){
	$link="http://huffingtonpost.com";
}

$theU= unshorten_url($link);
echo $_GET['callback'] . '(' . json_encode(array("status"=>"success", "data"=>$theU, "perm"=>doIt($theU))).")";



function doIt($url){

	$header = get_headers($url, 1);
	return $header["X-Frame-Options"];
}


function unshorten_url($url) {
  $ch = curl_init($url);
  curl_setopt_array($ch, array(
    CURLOPT_FOLLOWLOCATION => TRUE,  // the magic sauce
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_SSL_VERIFYHOST => FALSE, // suppress certain SSL errors
    CURLOPT_SSL_VERIFYPEER => FALSE, 
  ));
  curl_exec($ch); 
  return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
}

?>