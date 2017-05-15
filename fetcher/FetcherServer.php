<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

header('Content-Type: text/html; charset=utf-8');

require dirname(__DIR__) . '/vendor/autoload.php';
require 'FetcherDatabaseWrapper.php';

function parseUTF8( $msg ){

       return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                }, $msg); 
    }

class FetcherServer implements MessageComponentInterface {
    protected $clients;
    protected $database;
    protected $data;

    //gravar no log e enviar para todos clientes conectados;
    public function __construct() {
        $this->clients   = new \SplObjectStorage;
        $this->database  = new FetcherDatabaseWrapper();
    }

    public function getTwitterNews($institute_name){
        
        /* log */ $this->log_and_send( "fetching on [ TWITTER ]", 3);

        $twitterSettings = array(
            'oauth_access_token' => "710259368063803392-J3iAEJvvYjphXLKgUUslhGczx7zUXhf",
            'oauth_access_token_secret' => "HPQOpDJEkrcfky0CsEGMP5M5knSM3UxdrLtMMA46hU5sm",
            'consumer_key' => "oxTT30Qbwkx2YSZ7WBonkkNj3",
            'consumer_secret' => "Z52UUJsvaf6UsYphiKd6GErR6U5oBRkP3HgftOorK3mjJVV3Pa"
        );

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

        $last_fetched_key  = $this->data[$institute_name]["TWITTER"]["LAST_FETCHED_KEY"];
        $twitter_user_name = $this->data[$institute_name]["TWITTER"]["USER_NAME"];

        if($last_fetched_key == ""){
            $getfield = '?screen_name=' . $twitter_user_name . '&count=5';
            /* log */ $this->log_and_send( "No lastFetchedKey found. First time running.", 4 );
        }
        else{
            $getfield = '?screen_name=' . $twitter_user_name . '&since_id=' .  $last_fetched_key;
            /* log */ $this->log_and_send( "last fetched key: " . $last_fetched_key, 4 );
        }

        $requestMethod = 'GET';

        $twitter = new TwitterAPIExchange($twitterSettings);
        $response = $twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        $tweets = json_decode($response);
        
        $tweetes_array = [];

        $count = count($tweets);
        /* log */ $this->log_and_send( "results round: [ {$count} ] ", 4);

        foreach ($tweets as $position => $tweetObject) {

            $tweet_id           = "";
            $tweet_created_at   = "";
            $tweet_text         = "";
            $tweet_link         = "";
            $tweet_image        = "";
            
            if( isset( $tweetObject->id ) )
                $tweet_id = $tweetObject->id ;

            if( isset( $tweetObject->created_at ) )
                $tweet_created_at = $tweetObject->created_at; 

            if( isset( $tweetObject->text ) )
                $tweet_text = $tweetObject->text;

            if( isset( $tweetObject->entities->urls[0]->expanded_url ) )
                $tweet_link =  $tweetObject->entities->urls[0]->expanded_url ;

            if( isset( $tweetObject->entities->media[0]->media_url ) )
                $tweet_image = $tweetObject->entities->media[0]->media_url ;                    

            $tweet_array = array(
                                    "id"            => $tweet_id, 
                                    "created_at"    => $tweet_created_at, 
                                    "text"          => $tweet_text,
                                    "link"          => $tweet_link,
                                    "image"         => $tweet_image 
                                ); 

            /* log */ $this->log_and_send( "Key: [ " . $tweet_id . " ] fetched.", 5 );                       
            
            array_push( $tweetes_array, $tweet_array );    
        }

        $this->data[$institute_name]["TWITTER"]["NEWS"] = $tweetes_array; 

    }

    public function getFacebookNews( $institute_name ){
         
        /* log */ $this->log_and_send( "fetching on [ FACEBOOK ]", 3);
        
        //CHECK IF INSTITUTE EXISTS IN DATA LOADED   
        if( !isset($this->data[$institute_name]) ) {
            /* log */ $this->log_and_send("Institute [ " . $institute_name . " ] not found in our Data", 4 );

        //CHECK  IF INSTITUTE HAS FACEBOOK BROADCASTER    
        } else if( !isset($this->data[$institute_name]["FACEBOOK"] ) ) {
            /* log */ $this->log_and_send("Institute [ " . $institute_name . " ] do not have a FACEBOOK broadcaster.", 4 );
        
        //FETCH FACEBOOK NEWS
        } else {        

            //CHECK IF IS THE FIRST TIME RUNNING AND, IF DONT, GET LAST KEY 
            $lastFetchedKey = $this->data[$institute_name]["FACEBOOK"]["LAST_FETCHED_KEY"];
            $pagePostsQtdlimit = "";

            if( $lastFetchedKey != "" ) {
                //$lastFetchedKey = $this->data[$institute_name]["FACEBOOK"]["LAST_FETCHED_KEY"];
                /* log */ $this->log_and_send( "last fetched key: " . $lastFetchedKey, 4 );
            }    
            else {
                $pagePostsQtdlimit = "&limit=5";
                $lastFetchedKey = 0;
                /* log */ $this->log_and_send( "No lastFetchedKey found. First time running.", 4 );
            }
                 
            $facebook_page_id = $this->data[$institute_name]["FACEBOOK"]["USER_NAME"];     
            //DATA FOR THE FIRST PAGE REQUEST
            $acess_token = "1762035860779758|suyedAZYN2rE9eEaKXs1yejme3I";
            $fields = "?fields=id,created_time,type,name,message,description,link,full_picture" . $pagePostsQtdlimit;
            $graph_api = "https://graph.facebook.com/v2.8/";
            $request_link = $graph_api . $facebook_page_id . "/posts" . $fields . "&access_token=" . $acess_token;

            $pageNumber = 1;

            do {
                /* log */ $this->log_and_send( "getting page number [ ".$pageNumber." ]", 4 );
                //GETTING PAGE DATA
                $data = file_get_contents($request_link);
                
                $data = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                }, $data);

                $data = json_decode($data, true);
                
                $page_last_post_index   = count($data["data"])-1;
                $page_last_post         = $data["data"][$page_last_post_index];
                $page_last_post_key     = explode( "_", $page_last_post["id"] )[1];

                $arrayPosts = [];
                
                //GETTING POSTS FROM THE PAGE
                foreach ($data as $type => $content) {
                    if($type == "data"){
                        foreach ($content as $pos => $post) {

                            $key            = "" ;
                            $created_time   = "" ;
                            $type           = "" ;
                            $name           = "" ;
                            $message        = "" ;
                            $description    = "" ;
                            $link           = "" ;
                            $full_picture   = "" ;

                            if( isset( $content[$pos]["id"] ) )
                                $key = explode("_", $content[$pos]["id"])[1];

                            if( isset( $content[$pos]["created_time"] ) ) 
                                $created_time = $content[$pos]["created_time"];

                            if( isset( $content[$pos]["type"] ) ) 
                                $type = $content[$pos]["type"];

                            if( isset( $content[$pos]["name"] ) ) 
                                $name = $content[$pos]["name"];

                            if( isset( $content[$pos]["message"] ) ) 
                                $message = $content[$pos]["message"];

                            if( isset( $content[$pos]["description"] ) ) 
                                $description = $content[$pos]["description"];

                            if( isset( $content[$pos]["link"] ) ) 
                                $link = $content[$pos]["link"];    

                            if( isset( $content[$pos]["full_picture"] ) ) 
                                $full_picture = $content[$pos]["full_picture"];    

                            $modified_full_post = array(
                                                            "id"            => $key,
                                                            "created_time"  => $created_time,
                                                            "type"          => $type,
                                                            "name"          => $name,
                                                            "message"       => $message,
                                                            "description"   => $description,
                                                            "link"           => $link,
                                                            "full_picture"  => $full_picture
                                                    );

                            if ( $key > $lastFetchedKey ) {
                                array_push($arrayPosts, $modified_full_post);
                                /* log */ $this->log_and_send( "News: [ " . $key . " ] fetched.", 5 );
                            }    
                        }     
                    }  
                }

                // GETTING THE NEXT PAGE, IF EXISTS
                $request_link = "";
                if( isset( $data["paging"]["next"] ) ) 
                    $request_link = $data["paging"]["next"];

            // LOOP WHEN -> REQUEST LINK EXISTS, IS NOT THE FIRST FETCH, DIDNT FINDED THE LAST POST KEY YET. 
            } while ( ($request_link != "") && ($lastFetchedKey =! 0) && ($page_last_post_key > $lastFetchedKey) );

            $count = count( $arrayPosts );
            /* log */ $this->log_and_send( "results round: [ {$count} ] ", 4);

            $this->data[$institute_name]["FACEBOOK"]["NEWS"] = $arrayPosts; 
 
        }

    }

    public function fetchAllNews(){

        /* save execution time */
            date_default_timezone_set('America/Fortaleza');
            $time = date("Y-m-d H:i:s");
            $sql = 'INSERT INTO Execution (type, timestamp) values("fetcher", "' . date("d-m-Y H:i:s") . '")'; 
            $this->database->query($sql);
            if( $this->database->error() )  
                $this->log_and_send("error saving fetcher execution timestamp:" . $this->database->error(), 1 ); 
        /* save execution time */

        /* log */ $this->log_and_send( "fetching all news" , 1);
        foreach ($this->data as $institute_name => $broadcaster_array) {
            /* log */ $this->log_and_send( "fetching [ " . $institute_name . " ] news.", 2);
            foreach ($broadcaster_array as $broadcaster_name => $broadcaster_properties) {
                                
                switch ( $broadcaster_name ) {
                    
                    case 'SITE':
                        /* log */ $this->log_and_send( "fetching on [ SITE ]", 3);
                        break;

                    case 'FACEBOOK':
                        $this->getFacebookNews( $institute_name );

                        if( count($this->data[$institute_name]["FACEBOOK"]["NEWS"]) > 0 )
                            $this->saveFacebookNews( $institute_name );

                        break;

                    case 'TWITTER':
                        $this->getTwitterNews( $institute_name );

                        if( count($this->data[$institute_name]["TWITTER"]["NEWS"]) > 0 )
                            $this->saveTwitterNews( $institute_name );

                        break;   
                         
                    default:
                        break;
                }
            }
            /* log */ $this->log_and_send( "fetching on [ ".$broadcaster_name." ] END", 3);
        }
        /* log */ $this->log_and_send( "fetching all news FINISHED", 1);
    }
    
    public function saveFacebookNews( $institute_name ){
        /* log */ $this->log_and_send( "saving [ FACEBOOK ] news.", 4);

        $ib_id = $this->data[$institute_name]["FACEBOOK"]["IB_ID"];

        // INVERTE O ARRAY DE NOTICIAS PARA SALVAR PRIMEIRO AS MAIS VELHAS
        $news  = array_reverse( $this->data[$institute_name]["FACEBOOK"]["NEWS"] );

        foreach ($news as $pos => $post) {

            $access_key     = $post["id"];                                       // access_key
            $created_time   = $post["created_time"];                             // created_time
            $type           = $post["type"];                                         // type
            $name           = mysql_escape_mimic( $post["name"] );            // title
            $message        = mysql_escape_mimic( $post["message"]  );         // content
            $description    = mysql_escape_mimic( $post["description"]  );     // expanded_content
            $link           = $post["link"];                                         // shared_link
            $full_picture   = $post["full_picture"];                                 // full_picture_link      

            date_default_timezone_set('America/Fortaleza');
            $created_time = date("Y-m-d H:i:s", strtotime($created_time));

            $sql = "INSERT INTO news (access_key, created_time, type, title, content, expanded_content, shared_link, full_picture_link) VALUES ('"
                    .$access_key."', '"
                    .$created_time."', '"
                    .$type."', '"
                    .$name."', '"
                    .$message."', '"
                    .$description."', '"
                    .$link."', '"
                    .$full_picture."');\n\n";         

            if ($this->database->query($sql) === TRUE) {

                $sql = "INSERT INTO Institute_has_Broadcaster_has_News(Institute_has_Broadcaster_id, News_id) VALUES ('"
                    .$ib_id."', '"
                    .$this->database->insert_id() . "');\n\n";
                
                if ($this->database->query($sql) === TRUE) { 
                    /* log */ $this->log_and_send( "key [ {$access_key} ] saved.", 5);        
                } else {
                    /* log */ $this->log_and_send( "FATAL ERROR saving key [ {$access_key} ].", 4);     
                    /* log */ $this->log_and_send( "Error: " . $sql . "<br>" . $this->database->error(), 4);
                    exit;
                }  

            } else {
                /* log */ $this->log_and_send( "FATAL ERROR saving key [ {$id} ].", 4);     
                /* log */ $this->log_and_send( "Error: " . $sql . "<br>" . $this->database->error(), 4);
                exit;
            }
        }
    }

    public function saveTwitterNews( $institute_name ){
        /* log */ $this->log_and_send( "saving [ TWITTER ] news.", 4);

        $ib_id = $this->data[$institute_name]["TWITTER"]["IB_ID"];

        // INVERTE O ARRAY DE NOTICIAS PARA SALVAR PRIMEIRO AS MAIS VELHAS
        $news  = array_reverse( $this->data[$institute_name]["TWITTER"]["NEWS"] );

        foreach ($news as $pos => $post) {
                                                                                 // DATA BASE FIELDS
            $id             = $post["id"];                                           // acess_key
            $created_at     = $post["created_at"];                                   // created_time
            
            $text           = $post["text"] ; 
            $text           = replace4byte( $text ); 
         
            $expanded_url   = $post["link"];                                         // shared_link
            $media_url      = $post["image"];                                        // full_picture_link

            date_default_timezone_set('America/Fortaleza');
            $created_at = date("Y-m-d H:i:s", strtotime($created_at));

            $sql = "INSERT INTO news (access_key, created_time, content, shared_link, full_picture_link) VALUES ('"
                    .$id."', '"
                    .$created_at."', '"
                    .$text."', '"
                    .$expanded_url."', '"
                    .$media_url."');\n\n";
                            
            if ($this->database->query($sql) === TRUE) {

                $sql = "INSERT INTO Institute_has_Broadcaster_has_News(Institute_has_Broadcaster_id, News_id) VALUES ('"
                    .$ib_id."', '"
                    .$this->database->insert_id()."');\n\n";
                
                if ($this->database->query($sql) === TRUE) { 
                    /* log */ $this->log_and_send( "key [ {$id} ] saved.", 5);        
                } else {
                    /* log */ $this->log_and_send( "FATAL ERROR saving key [ {$id} ].", 4);     
                    /* log */ $this->log_and_send( "Error: " . $sql . "<br>" . $this->database->error(), 4);
                    exit;
                }  

            } else {
                /* log */ $this->log_and_send( "FATAL ERROR saving key [ {$id} ].", 4);     
                /* log */ $this->log_and_send( "Error: " . $sql . "<br>" . $this->database->error(), 4);
                exit;
            }
        }    
    }

    public function loadConfigs(){
        
        /* log */ $this->log_and_send("loading initial data.", 1);

        $configs = [];
        $result = $this->database->getConfigs();
        
        if ( $result->num_rows > 0 ) {
            while( $row = $result->fetch_assoc() ) {
                        
                $ib_id              = $row["ib_id"];
                $institute_name     = $row["institute_name"];
                $broadcaster_name   = $row["broadcaster_name"];
                $user_name          = $row["userName"];
                $link               = $row["link"];
                $last_key           = $row["last_news_access_key"];

                if( !isset($configs[$institute_name]) )
                    $configs[$institute_name] = array();  

                if( !isset($configs[$institute_name][$broadcaster_name]) )
                    $configs[$institute_name][$broadcaster_name] = array();

                $configs[$institute_name][$broadcaster_name]["IB_ID"]     = $ib_id;     
                $configs[$institute_name][$broadcaster_name]["USER_NAME"] = $user_name;
                $configs[$institute_name][$broadcaster_name]["LINK"]      = $link;
                $configs[$institute_name][$broadcaster_name]["LAST_FETCHED_KEY"] = $last_key;
                $configs[$institute_name][$broadcaster_name]["NEWS"] = array();
            } 
        } else {
            /* log */ $this->log_and_send("No initial data found. " . $this->database->error(), 2);
        }

        $this->data = $configs;

    }

    public function onMessage( ConnectionInterface $from, $msg ) {
        
        /* log */ $this->log_and_send( "client [ " . $from->resourceId . " ] requested [ " . $msg. " ]", 0 );

        switch ($msg) {   
            case "fetch":
                $this->loadConfigs();
                $this->fetchAllNews();
                break;
            default:
                /* log */ $this->log_and_send( "Requested message [ " . $msg . " ] not supported.", 0 );
                break;
        }
        
        /* log */ $this->log_and_send( "client [ " . $from->resourceId . " ] requested [ " . $msg. " ] FINISHED", 0 );
    }

    public function onOpen( ConnectionInterface $conn ) {
        $this->clients->attach($conn);
        /* log */ $this->log_and_send( "New connection! [ $conn->resourceId ].", 0 );
    }
    public function onClose( ConnectionInterface $conn ) {
        $this->clients->detach($conn);
        /* log */ $this->log_and_send( "Connection {$conn->resourceId} has disconnected.", 0 ); 
    }
    public function onError( ConnectionInterface $conn, \Exception $e ) {
        /* log */ $this->log_and_send( "An error has occurred: {$e->getMessage()}", 0 );
        $conn->close(); 
    }
    private function log( $msg ){
        $fd = fopen("fetcherLog.txt", "a"); 
        fwrite($fd, $msg ); 
        fclose($fd);
    }
    private function send( $msg ){
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }    
    private function log_and_send( $msg, $lvl ){
        
        $ident = "";

        for( $i = 0; $i<$lvl; $i++ ){
            $ident .= "     ";
        }
        
        date_default_timezone_set('America/Fortaleza');
        $data = date('Y-m-d H:i:s');
        
        $msg = $data. ":  " . $ident.$msg."\n";

        $this->log ( $msg );
        echo $msg;
        
        if( count($this->clients) > 0 )
            $this->send( $msg );
    }

} // end fetch server class

$port = 8387;
$server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new FetcherServer()
            )
        ),
        $port
    );
    
echo "\tserver started on port {$port}...\n\n";    
$server->run();

?>