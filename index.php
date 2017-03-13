<?php
//error_reporting(E_ALL ^ E_WARNING);

include 'config.php';
//require_once('TwitterAPIExchange.php');
require 'vendor/autoload.php';

//echo $array["ITA"]["SITE"];

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


$mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "\n";

//debug($mysqli);

/* 

GETTING TWITTES FROM USERS

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
foreach ($institutes as $institute => $newsChannels) {
    foreach ($newsChannels as $newsChannel => $link) {
        echo $institute . "-" . $newsChannel . "-" . $link . "<br/>";
    }
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

