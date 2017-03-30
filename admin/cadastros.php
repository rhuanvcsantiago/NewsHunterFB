<div class="table-responsive table-striped ">
  <table class="table table-hover">
    <thead>
        <tr>
            <th> institute_name </th>
            <th> broadcaster_name </th>
            <th> link </th>
        </tr>
    </thead>
    <tbody>    

<?php

$mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$sql = "SELECT * FROM configs";
$result = $mysqli->query($sql);

echo "<tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        echo "<tr>";
        echo "<td>" . $row["institute_name"] . "</td>"; 
        echo "<td>" . $row["broadcaster_name"] . "</td>"; 
        echo "<td>" . $row["link"] . "</td>"; 
        echo "</tr>";         
      
    }

    echo "</tr>"; 

    echo "CONFIGURAÇAO CARREGADA COM SUCESSO.<br/>";
    echo "<br/>";
    //debug($institutes_and_broadcasters);    
} else {
    echo "FALHA CRITICA -> NO CARREGAMENTO DAS CONFIGURACOES.<BR/>";
    echo "SCRIPT ENCERRADO";
    exit;
}

$mysqli->close();


?>

        </tbody>
    </table>
</div>