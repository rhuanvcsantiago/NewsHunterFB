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


class FetcherDatabaseWrapper {
    private $mysqli = null;

    function __construct(){ 
        
        $mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");

        if ( $mysqli->connect_errno ) {
            throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error); 
        } else {
            $this->mysqli = $mysqli;
           // mysql_query("SET NAMES 'utf8'");
           // mysql_query('SET character_set_connection=utf8');
           // mysql_query('SET character_set_client=utf8');
           // mysql_query('SET character_set_results=utf8');
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