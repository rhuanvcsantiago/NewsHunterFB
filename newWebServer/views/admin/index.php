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
    <li>
        <h3>[ <a>2017-05-01 19:00:00</a> ] foi ultima data de execução do fetcher.</h3>
    </li>
    <br />
    <li>
        <h3>[ <a>2017-05-01 19:00:00</a> ] foi ultimo envio de emails.</h3>
    </li>
    <br />
<ul>
<br />


