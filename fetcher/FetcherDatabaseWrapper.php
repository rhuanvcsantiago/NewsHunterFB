<?php 

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

function replace4byte($string) {
    return preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
    )%xs', '', $string);    
}


class FetcherDatabaseWrapper {
    private $mysqli = null;

    function __construct(){ 
        
        $mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");

        if ( $mysqli->connect_errno ) {
            throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error); 
        } else {
            $this->mysqli = $mysqli;
            printf("Initial database character set: %s\n", $this->mysqli->character_set_name());

            /* change character set to utf8 */
            if (!$mysqli->set_charset("utf8mb4")) {
                printf("Error loading database character set utf8mb4: %s\n", $this->mysqli->error);
                exit();
            } else {
                printf("Current database character set: %s\n", $this->mysqli->character_set_name());
            }
        }  
    }

    public function query( $sql ){

        return $this->mysqli->query( $sql );

    }

    public function error(){

        return $this->mysqli->error;

    }

     public function insert_id(){

        return $this->mysqli->insert_id;

    }

    public function getConfigs(){

        $sql = "SELECT * FROM configs";
        return $this->query($sql);
    }

    public function saveTwitterNews($ib_id, $news){
        
        
    }
}

//$db = new FetcherDatabaseWrapper();
//debug($db->getConfigs());


?>