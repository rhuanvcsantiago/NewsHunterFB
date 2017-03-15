<?php
//error_reporting(E_ALL ^ E_WARNING);

include 'config.php';
//require_once('TwitterAPIExchange.php');
require 'vendor/autoload.php';

//echo $array["ITA"]["SITE"];
//log in console
//error_log("Oracle database not available!", 0);
function debug() {
    $trace = debug_backtrace();
    $rootPath = dirname(dirname(__FILE__));
    $file = str_replace($rootPath, '', $trace[0]['file']);
    $line = $trace[0]['line'];
    $var = $trace[0]['args'][0];
    $lineInfo = sprintf('<div><strong>%s</strong> (line <strong>%s</strong>)</div>', $file, $line);
    $debugInfo = sprintf('<pre>%s</pre>', print_r($var, true));
    print_r($lineInfo.$debugInfo);
}

function fechNews($institutes_and_broadcasters){

    foreach ($institutes_and_broadcasters as $institute_name => $broadcaster_array) {
        foreach ($broadcaster_array as $broadcaster_name => $broadcaster_properties) {
            switch ($broadcaster_name) {
                case 'SITE':
                    break;
                case 'FACEBOOK':
                    $institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"] = fetchFacebookNews($broadcaster_properties["USER_NAME"]);
                    break;
                case 'TWITTER':
                    //echo $broadcaster_properties["USER_NAME"];
                    $institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"] = fetchTwitterNews($broadcaster_properties["USER_NAME"]);
                    break;    
                default:
                    break;
            }
        }
    }

    return $institutes_and_broadcasters;
}

function fetchTwitterNews($twitter_user_name){

    //PEGAR last fetched key from database

    $twitterSettings = array(
        'oauth_access_token' => "710259368063803392-J3iAEJvvYjphXLKgUUslhGczx7zUXhf",
        'oauth_access_token_secret' => "HPQOpDJEkrcfky0CsEGMP5M5knSM3UxdrLtMMA46hU5sm",
        'consumer_key' => "oxTT30Qbwkx2YSZ7WBonkkNj3",
        'consumer_secret' => "Z52UUJsvaf6UsYphiKd6GErR6U5oBRkP3HgftOorK3mjJVV3Pa"
    );

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    $getfield = '?screen_name=' . $twitter_user_name . '&count=2';
    $requestMethod = 'GET';

    $twitter = new TwitterAPIExchange($twitterSettings);
    $response = $twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest();

    $tweets = json_decode($response);

    $twiites_array = [];

    foreach ($tweets as $position => $tweetObject) {
        array_push($twiites_array, new News($tweetObject->created_at, $tweetObject->text, $tweetObject->id) );    
    }

    return $twiites_array;

}

function fetchFacebookNews($facebook_page_id){

    $acess_token = "1762035860779758|suyedAZYN2rE9eEaKXs1yejme3I";
    // retirar restricao de limite
    $fields = "?fields=id,created_time,type,name,message,description,link,story,picture,full_picture&limit=2";
    $graph_api = "https://graph.facebook.com/v2.8/";
    $request_link = $graph_api . $facebook_page_id . "/posts" . $fields . "&access_token=" . $acess_token;

    $data  = file_get_contents($request_link);
                                
    $data = json_decode($data, true);

    return $data;

}

function getInstitutesAndBroadcasters(){
    
    $institutes_and_broadcasters = [];

    $mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");
    
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    //  echo $mysqli->host_info . "\n";

    $sql = "SELECT institute_name, broadcaster_name, link, userName FROM institutes_and_broadcasters";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
           
           //debug($row);
           
            $institute_name     = $row["institute_name"];
            $broadcaster_name   = $row["broadcaster_name"];
            $user_name          = $row["userName"];
            $link               = $row["link"];

           if( !isset($institutes_and_broadcasters[$institute_name]) )
                $institutes_and_broadcasters[$institute_name] = array();  

           if( !isset($institutes_and_broadcasters[$institute_name][$broadcaster_name]) )
                $institutes_and_broadcasters[$institute_name][$broadcaster_name] = array();

           $institutes_and_broadcasters[$institute_name][$broadcaster_name]["USER_NAME"] = $user_name;
           $institutes_and_broadcasters[$institute_name][$broadcaster_name]["LINK"]      = $link;
           
           if( !isset($institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"]) )
                $institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"] = array();
        } 

    } else {
        echo "0 results";
    }

    $mysqli->close();

    return $institutes_and_broadcasters;
   
 }

class News{
    public $date;
    public $title;
    public $content;
    public $key;
    public $link;

    function __construct($created_at, $text, $id) {
        $this->date     = $created_at;
        $this->content  = $text;
        $this->key      = $id;
    }
}

//debug($mysqli);

$institutes_and_broadcasters = getInstitutesAndBroadcasters();
//debug($institutes_and_broadcasters);
$institutes_and_broadcasters = fechNews($institutes_and_broadcasters);
debug($institutes_and_broadcasters);

/*
//GETTING TWITTES FROM USERS

$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$getfield = '?screen_name=ime1792';
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($twitterSettings);
$response = $twitter->setGetfield($getfield)
    ->buildOauth($url, $requestMethod)
    ->performRequest();

$tweets = json_decode($response);

debug($tweets);

/*
foreach ($tweets as $position => $tweetObject) {
    echo $tweetObject->created_at . "-" . $tweetObject->text . "<br/>";    
}

/*
# WHAT I NEED DO TO
    - GET NEWS ON EVERY CHANNEL
        - TEST TWITTER API
        - TEST FACEBOOK API
        - CREATE WEB SCRAPING
    - IDENTIFY THE LAST NEWS
    - SAVE THE LAST NEWS ON DATABASE


NEWSHUNTER
- INSTITUTES
+ load_configs() - load configs from file or database
+ fetch_all_last_news() - get all institute last news from all channels
+ fetch_all_last_news(channelType) - get all institute last news from all channels
+ fecth_institute_last_news(instituteName) - get last news from a especifit institute name
+ fecth_institute_last_news(instituteName, channelType) - get last news from a especifit institute name
+ insert_new_institute(instituteObject);
+ save_all_last_news();

INSTITUTE
- NAME
- NEWSCHANNELS 

CHANNEL
- TYPE
- LINK
- NEWS
- LAST_TIME_FETCHED

NEW
- DATE
- TITLE
- CONTENT
- LINK
- TAG
- IS_TO_SEND

TWITTER
- id
- date
- text

<?php
//error_reporting(E_ALL ^ E_WARNING);

include 'config.php';
//require_once('TwitterAPIExchange.php');
require 'vendor/autoload.php';

//echo $array["ITA"]["SITE"];

function debug() {…

*/
?>