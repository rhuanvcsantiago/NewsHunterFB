<HTML><meta charset="UTF-8">
<?php
//error_reporting(E_ALL ^ E_WARNING);

//include 'config.php';
require '../vendor/autoload.php';



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

function mysql_escape_mimic($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    } 

    return $inp; 
} 

function saveTwitterNews($broadcaster_properties){

   
    $ib_id = $broadcaster_properties["IB_ID"];
    $news  = $broadcaster_properties["NEWS"];

    $mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");
    if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    foreach ($news as $pos => $post) {
                                                                        //DATA BASE FIELDS
        $id             = $post["id"];           //acess_key
        $created_at     = $post["created_at"];    //created_time
        $text           = mysql_escape_mimic( utf8_encode( $post["text"] ) );          //content
        $expanded_url   = $post["link"];          //shared_link
        $media_url      = $post["image"];         //full_picture_link

        date_default_timezone_set('America/Fortaleza');
        $created_at = date("Y-m-d H:i:s", strtotime($created_at));

        $sql = "INSERT INTO news (access_key, created_time, content, shared_link, full_picture_link) VALUES ('"
                .$id."', '"
                .$created_at."', '"
                .$text."', '"
                .$expanded_url."', '"
                .$media_url."');\n\n";
        
       echo "salvando chave " . $id;
       echo "<br/>"; 
         
       if ($mysqli->query($sql) === TRUE) {

            $sql = "INSERT INTO Institute_has_Broadcaster_has_News(Institute_has_Broadcaster_id, News_id) VALUES ('"
                   .$ib_id."', '"
                   .$mysqli->insert_id."');\n\n";
            
            if ($mysqli->query($sql) === TRUE) { 
                echo "saved new;";
                echo "<br/>";
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }  

        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }

}


function saveFacebookNews($broadcaster_properties){

   // debug($broadcaster_properties);
   // exit;
    $ib_id = $broadcaster_properties["IB_ID"];
    $news  = $broadcaster_properties["NEWS"];

    $mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");
    if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

     foreach ($news as $pos => $post) {


        $access_key     = $post["id"];                                       // access_key
        $created_time   = $post["created_time"];                             // created_time
        $type           = $post["type"];                                         // type
        $name           = mysql_escape_mimic( utf8_encode( $post["name"] ));            // title
        $message        = mysql_escape_mimic( utf8_encode( $post["message"] ) );         // content
        $description    = mysql_escape_mimic( utf8_encode( $post["description"] ) );     // expanded_content
        $link           = $post["lnk"];                                         // shared_link
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

        echo "salvando chave " . $access_key; 
        echo "<br/>";

       if ($mysqli->query($sql) === TRUE) {

            $sql = "INSERT INTO Institute_has_Broadcaster_has_News(Institute_has_Broadcaster_id, News_id) VALUES ('"
                   .$ib_id."', '"
                   .$mysqli->insert_id."');\n\n";
            
            if ($mysqli->query($sql) === TRUE) { 
                echo "saved new;";
                echo "<br/>";
            } else {
                echo "Error: " . $sql . "<br>" . $mysqli->error;
            }  

        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }

    }
}

function saveNews($institutes_and_broadcasters){ 
   
    foreach ($institutes_and_broadcasters as $institute_name => $broadcaster_array) {
        foreach ($broadcaster_array as $broadcaster_name => $broadcaster_properties) { 
            if( count($institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"] ) > 0 ) {
                echo "Salvando news de " . $institute_name . "-" . $broadcaster_name;
                echo "<br/>";
                switch ($broadcaster_name) {
                    case 'SITE':
                        break;
                    case 'FACEBOOK':
                        saveFacebookNews( $broadcaster_properties );
                        break;
                    case 'TWITTER':
                        saveTwitterNews( $broadcaster_properties );
                        break;    
                    default:
                        break;
                }
            }
        }
    }
}

function fechNews($institutes_and_broadcasters){


    foreach ($institutes_and_broadcasters as $institute_name => $broadcaster_array) {
        foreach ($broadcaster_array as $broadcaster_name => $broadcaster_properties) {
            echo "Procurando news de " . $institute_name . "-" . $broadcaster_name . " ultima chave salva no banco " . $broadcaster_properties["LAST_FETCHED_KEY"];
            echo "<br/>";
            switch ($broadcaster_name) {
                case 'SITE':
                    break;
                case 'FACEBOOK':
                    $institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"] = fetchFacebookNews($broadcaster_properties["USER_NAME"], $broadcaster_properties["LAST_FETCHED_KEY"]);
                    break;
                case 'TWITTER':
                    $institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"] = fetchTwitterNews($broadcaster_properties["USER_NAME"], $broadcaster_properties["LAST_FETCHED_KEY"]);
                    break;    
                default:
                    break;
            }
            echo "<br/>";
        }
    }

    return $institutes_and_broadcasters;
}

function fetchTwitterNews($twitter_user_name, $last_fetched_key){

    $twitterSettings = array(
        'oauth_access_token' => "710259368063803392-J3iAEJvvYjphXLKgUUslhGczx7zUXhf",
        'oauth_access_token_secret' => "HPQOpDJEkrcfky0CsEGMP5M5knSM3UxdrLtMMA46hU5sm",
        'consumer_key' => "oxTT30Qbwkx2YSZ7WBonkkNj3",
        'consumer_secret' => "Z52UUJsvaf6UsYphiKd6GErR6U5oBRkP3HgftOorK3mjJVV3Pa"
    );

    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

    if($last_fetched_key == "")
        //busca por ultima key cadastrada no banco
        $getfield = '?screen_name=' . $twitter_user_name . '&count=5';
    else
        $getfield = '?screen_name=' . $twitter_user_name . '&since_id=' .  $last_fetched_key;

    $requestMethod = 'GET';

    $twitter = new TwitterAPIExchange($twitterSettings);
    $response = $twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest();

    $tweets = json_decode($response);
    
    $tweetes_array = [];

    foreach ($tweets as $position => $tweetObject) {

        $tweet_id           = "";
        $tweet_created_at   = "";
        $tweet_text         = "";
        $tweet_link         = "";
        $tweet_image        = "";
        
        if( isset( $tweetObject->id ) )
            $tweet_id = $tweetObject->id;

        if( isset( $tweetObject->created_at ) )
            $tweet_created_at = $tweetObject->created_at; 

        if( isset( $tweetObject->text ) )
            $tweet_text = $tweetObject->text;

        if( isset( $tweetObject->entities->urls[0]->expanded_url ) )
            $tweet_link = $tweetObject->entities->urls[0]->expanded_url;

        if( isset( $tweetObject->entities->media[0]->media_url ) )
            $tweet_image = $tweetObject->entities->media[0]->media_url;                    


        $tweet_array = array(
                                "id"            => $tweet_id, 
                                "created_at"    => $tweet_created_at, 
                                "text"          => $tweet_text,
                                "link"          => $tweet_link,
                                "image"         => $tweet_image 
                            );    
        
        array_push( $tweetes_array, $tweet_array );    
    }

    echo " - " . count($tweetes_array) . " resultados encontrados";
    echo "<br/>";
    return $tweetes_array;

}

function fetchFacebookNews($facebook_page_id, $last_fetched_key){

    // SE LAST FETCHED KEY NAO TA PREENCHIDA = PRIMEIRO ACESSO
    $limit = "";
    if($last_fetched_key == "")
        $limit = "&limit=5";

    $acess_token = "1762035860779758|suyedAZYN2rE9eEaKXs1yejme3I";
    $fields = "?fields=id,created_time,type,name,message,description,link,full_picture" . $limit;
    $graph_api = "https://graph.facebook.com/v2.8/";
    $request_link = $graph_api . $facebook_page_id . "/posts" . $fields . "&access_token=" . $acess_token;

    $data  = file_get_contents($request_link);            
    $data = json_decode($data, true);

    //debug($data);exit;

    $page_last_post_index = count($data["data"])-1;
    $page_last_post = $data["data"][$page_last_post_index];
    $page_last_post_key = explode( "_", $page_last_post["id"] )[1];

    $arrayPosts = [];
    
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
                    $created_time   = $content[$pos]["created_time"];

                if( isset( $content[$pos]["type"] ) ) 
                    $type   = $content[$pos]["type"];

                if( isset( $content[$pos]["name"] ) ) 
                    $name   = $content[$pos]["name"];

                if( isset( $content[$pos]["message"] ) ) 
                    $message   = $content[$pos]["message"];

                if( isset( $content[$pos]["description"] ) ) 
                    $description   = $content[$pos]["description"];

                if( isset( $content[$pos]["link"] ) ) 
                    $link   = $content[$pos]["link"];    

                if( isset( $content[$pos]["full_picture"] ) ) 
                    $full_picture   = $content[$pos]["full_picture"];    

                $modified_full_post = array(
                                                "id"            => $key,
                                                "created_time"  => $created_time,
                                                "type"          => $type,
                                                "name"          => $name,
                                                "message"       => $message,
                                                "description"   => $description,
                                                "lnk"           => $link,
                                                "full_picture"  => $full_picture
                                           );

                if ( $key > $last_fetched_key )
                    array_push($arrayPosts, $modified_full_post);
            }     
        }  
    } 
    
    while( $page_last_post_key > $last_fetched_key ){
        $request_link = $data["paging"]["next"];
        $data  = file_get_contents($request_link);            
        $data = json_decode($data, true);

        $page_last_post_index = count($data["data"])-1;
        $page_last_post = $data["data"][$page_last_post_index];
        $page_last_post_key = explode( "_", $page_last_post["id"] )[1];

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
                    $created_time   = $content[$pos]["created_time"];

                if( isset( $content[$pos]["type"] ) ) 
                    $type   = $content[$pos]["type"];

                if( isset( $content[$pos]["name"] ) ) 
                    $name   = $content[$pos]["name"];

                if( isset( $content[$pos]["message"] ) ) 
                    $message   = $content[$pos]["message"];

                if( isset( $content[$pos]["description"] ) ) 
                    $description   = $content[$pos]["description"];

                if( isset( $content[$pos]["link"] ) ) 
                    $link   = $content[$pos]["link"];    

                if( isset( $content[$pos]["full_picture"] ) ) 
                    $full_picture   = $content[$pos]["full_picture"];    

                $modified_full_post = array(
                                                "id"            => $key,
                                                "created_time"  => $created_time,
                                                "type"          => $type,
                                                "name"          => $name,
                                                "message"       => $message,
                                                "description"   => $description,
                                                "lnk"           => $link,
                                                "full_picture"  => $full_picture
                                           );

                if ( $key > $last_fetched_key )
                    array_push($arrayPosts, $modified_full_post);
                }     
            }  
        } 
    }
    
    echo " - " . count($arrayPosts) . " resultados encontrados";
    echo "<br/>";
    return $arrayPosts;
}

function getInstitutesAndBroadcasters(){
    
    $institutes_and_broadcasters = [];

    $mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");
    
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    $sql = "SELECT * FROM configs";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
                      
            $ib_id              = $row["ib_id"];
            $institute_name     = $row["institute_name"];
            $broadcaster_name   = $row["broadcaster_name"];
            $user_name          = $row["userName"];
            $link               = $row["link"];
            $last_key           = $row["last_news_access_key"];

           if( !isset($institutes_and_broadcasters[$institute_name]) )
                $institutes_and_broadcasters[$institute_name] = array();  

           if( !isset($institutes_and_broadcasters[$institute_name][$broadcaster_name]) )
                $institutes_and_broadcasters[$institute_name][$broadcaster_name] = array();

           $institutes_and_broadcasters[$institute_name][$broadcaster_name]["IB_ID"]     = $ib_id;     
           $institutes_and_broadcasters[$institute_name][$broadcaster_name]["USER_NAME"] = $user_name;
           $institutes_and_broadcasters[$institute_name][$broadcaster_name]["LINK"]      = $link;
           $institutes_and_broadcasters[$institute_name][$broadcaster_name]["LAST_FETCHED_KEY"] = $last_key;
           $institutes_and_broadcasters[$institute_name][$broadcaster_name]["NEWS"] = array();
        } 

        echo "CONFIGURAÇAO CARREGADA COM SUCESSO.<br/>";
        echo "<br/>";
        //debug($institutes_and_broadcasters);    
    } else {
        echo "FALHA CRITICA -> NO CARREGAMENTO DAS CONFIGURACOES.<BR/>";
        echo "SCRIPT ENCERRADO";
        exit;
    }

    $mysqli->close();

    return $institutes_and_broadcasters;
   
 }

$institutes_and_broadcasters = getInstitutesAndBroadcasters();
//debug($institutes_and_broadcasters);
$institutes_and_broadcasters = fechNews($institutes_and_broadcasters);
//debug($institutes_and_broadcasters);
saveNews($institutes_and_broadcasters);


?>