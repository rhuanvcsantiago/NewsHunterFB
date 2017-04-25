<button  id="enviarEmails" type="button" class="btn btn-primary btn-lg">Enviar noticias abaixo por emails</button>

<div class="table-responsive table-striped ">
  <table class="table table-hover">
    <thead>
        <tr>
            <th> institute_name </th>
            <th> broadcaster_name </th>
            <th> news_id </th>
            <th> news_title </th>
            <th> news_content </th>

        </tr>
    </thead>
    <tbody> 

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

$mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$sql = "SELECT institute_name, broadcaster_name, news_created_time, news_title, news_content, news_expanded_content, news_shared_link, news_full_picture_link, news_id FROM  NewsHunterFFB.ibn where is_relevant = true and mail_sent is null order by institute_name, broadcaster_name;";
$result = $mysqli->query($sql);

echo "<tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        echo "<tr>";
        echo "<td>" . $row["institute_name"] . "</td>"; 
        echo "<td>" . $row["broadcaster_name"] . "</td>"; 
        echo "<td>" . $row["news_id"] . "</td>"; 
        echo "<td>" . $row["news_title"] . "</td>"; 
        echo "<td>" . $row["news_content"] . "</td>"; 

        echo "</tr>";         
      
    }

    echo "</tr>"; 

    echo "<br/>";
    //debug($institutes_and_broadcasters);    
} else {
    echo "FALHA CRITICA -> NO CARREGAMENTO DAS CONFIGURACOES.<BR/>";
    echo "SCRIPT ENCERRADO";
    exit;
}


?>