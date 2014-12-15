<?php
ini_set('display_errors', 1);
require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');

require_once('TwitterAPIExchange.php');


    
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/

//return;
//$results1=array_reverse($results);

//$results = $results1;

//get all the topics

$topics = dbMassData("SELECT * FROM topics GROUP BY topic LIMIT 100");

for($j=0; $j<count($topics); $j++){
    $topic = $topics[$j]['topic'];




    $settings = array(
    'oauth_access_token' => "181814332-Z1o6ioivOxNYoMth4eQoMaNIdJA772P38ancYHkJ",
    'oauth_access_token_secret' => "nQRHTW4rleq2R9k1Yu7eqpaegrdZkuI1n0YiRfvtQOpw4",
    'consumer_key' => "5glEetZUGNwfLweCWC9m6a3My",
    'consumer_secret' => "Yfmqilml5scV3Fvn6Rc6YrvnanxAg01KSD4VNYI4FEfkSmvQuo"
);



$topic= strtolower($topic);
/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
$url = 'https://api.twitter.com/1.1/search/tweets.json';
$requestMethod = 'GET';

$getfield = '?q='.urlencode($topic).'&result_type=popular';


$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);

$results= $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();
 //return;
           //  echo($res);
//return;
             echo($results);
             echo("<br><br><br><br><br><br><br>");

$res= json_decode($results, true);

$results= $res['statuses'];

//print_r($results);
$results1 = $results;
$results = array_reverse($results1);



for($i =0; $i<count($results); $i++){

    echo("hey");
    if( isset($results[$i]['entities']['urls'][0])){

        $cont = $results[$i]['text'];

       $string = $cont;
        $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
        $cont= preg_replace($regex, ' ', $string);

        
        $sourceId=0;
        $sourceUrl = "http://twitter.com";
        $url = $results[$i]['entities']['urls'][0]['expanded_url'];
     

            $imageByTopic= dbMassData("SELECT * FROM articles WHERE topic = '$topic' AND image !='' AND image NOT LIKE '%black.png' AND image NOT LIKE '%nyt.png' AND sourceId != $sourceId AND sourceId!=7 ORDER BY timestamp DESC LIMIT 5");
            echo('wowa');
            //echo("SELECT * FROM articles WHERE topic = '$topic' AND image !='' AND sourceId != $sourceId ORDER BY timestamp DESC LIMIT 5");

            if(count($imageByTopic) >4){
                echo('greater');
            $which = rand(0,4);
            echo("number is". $which);
            $image1 = $imageByTopic[$which]['image'];
            echo('image is '. $image1);
            }
         
        
        $cont = str_replace("'","`", $cont);
        $exists = dbMassData("SELECT * FROM articles WHERE url = '$url'");
       // echo("INSERT INTO articles(article, sourceId, sourceUrl, url, headline, topic, image) VALUES ('$cont', $sourceId, '$sourceUrl', '$url', '$headline', '$topic', '$image1')");
        if($exists==null){
            echo('image1 is'. $image1);
           dbQuery("INSERT INTO articles(article, sourceId, sourceUrl, url, headline, topic, image) VALUES ('$cont', $sourceId, '$sourceUrl', '$url', '$headline', '$topic', '".$image1."');");
            echo("INSERT INTO articles(article, sourceId, sourceUrl, url, headline, topic, image) VALUES ('$cont', $sourceId, '$sourceUrl', '$url', '$headline', '$topic', '".$image1."');");


        }
    }

}
}


