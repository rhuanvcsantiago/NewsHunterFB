<?php

    $result = array_reverse($result);

    $ibn_array = [];

    foreach ($result as $position => $ibn) {

        $institute_name         = $ibn["institute_name"];
        $broadcaster_name       = $ibn["broadcaster_name"];
        $news_created_time      = $ibn["news_created_time"];
        $news_title             = $ibn["news_title"];
        $news_content           = $ibn["news_content"];
        $news_expanded_content  = $ibn["news_expanded_content"];
        $news_shared_link       = $ibn["news_shared_link"];
        $news_full_picture_link = $ibn["news_full_picture_link"];
        $news_id                = $ibn["news_id"];

        if( !isset( $ibn_array[$institute_name] ) )
            $ibn_array[$institute_name] = [];

        if( !isset( $ibn_array[$institute_name][$broadcaster_name] ) )
            $ibn_array[$institute_name][$broadcaster_name] = [];   

        if( !isset( $ibn_array[$institute_name][$broadcaster_name][$news_id] ) )
            $ibn_array[$institute_name][$broadcaster_name][$news_id] = [];    
        
        $ibn_array[$institute_name][$broadcaster_name][$news_id]["news_created_time"]      = $news_created_time; 
        $ibn_array[$institute_name][$broadcaster_name][$news_id]["news_title"]             = $news_title;
        $ibn_array[$institute_name][$broadcaster_name][$news_id]["news_content"]           = $news_content;
        $ibn_array[$institute_name][$broadcaster_name][$news_id]["news_expanded_content"]  = $news_expanded_content;
        $ibn_array[$institute_name][$broadcaster_name][$news_id]["news_shared_link"]       = $news_shared_link;
        $ibn_array[$institute_name][$broadcaster_name][$news_id]["news_full_picture_link"] = $news_full_picture_link;

    }
    
    $countTotal = 0;

    $tableString = "";
    $tableString .= '<div class="table-responsive">';
    $tableString .= '<table class="table table-hover">';
        $tableString .= '<tr>';
                $tableString .= '<th>';
                    $tableString .= "Instituto";
                $tableString .= '</th>';

                $tableString .= '<th>';
                    $tableString .= "Broadcaster";
                $tableString .= '</th>';

                $tableString .= '<th>';
                    $tableString .= "Qtd";
                $tableString .= '</th>';
            $tableString .= '</tr>';

    foreach ($ibn_array as $institute_name => $broadcaster_array) { 

        foreach ($broadcaster_array as $broadcaster_name => $news_array) {

            $tableString .= '<tr>';
                $tableString .= '<td>';
                    $tableString .= $institute_name;
                $tableString .= '</td>';

                $tableString .= '<td>';
                    $tableString .= $broadcaster_name;
                $tableString .= '</td>';

                $tableString .= '<td>';
                    $countTotal  += count($ibn_array[$institute_name][$broadcaster_name]);
                    $tableString .= count($ibn_array[$institute_name][$broadcaster_name]);
                $tableString .= '</td>';
            $tableString .= '</tr>';
        }
    }
    $tableString .= '</table>';
    $tableString .= '</div>'; // closing panel-group; */
?>


<h1>Bem vindo, Administrador!</h1>
<ul>
    <br />
    <li>
        <h3 style="display:inline;"> Você têm [ <a><?php echo $countTotal?></a> ] noticias para classificar. </h3>
        <br />
        <br />
        <?php echo $tableString?>
    </li>
    <br />

    <?php 
        foreach ($lastExecutions as $position => $lastExecution) {
            
            if($lastExecution["type"] == "fetcher"){
                echo "<li><h3>[ <a>".$lastExecution["lastExecutionTime"]."</a> ] foi ultima data de execução do fetcher.</h3></li>";
            } else {
                echo "<li><h3>[ <a>".$lastExecution["lastExecutionTime"]."</a> ] foi ultimo envio de emails.</h3></li>"; 
            }

            echo "<br />";

        }
    
    ?>

<ul>
<br />


