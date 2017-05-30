<h1> OLÁ, "USUARIO"! </h1>
<h3> ALGUMAS NOTICIAS QUE VOCE PERDEU! </h3>
<br />
<br />
<br />
<?php

    $result = array_reverse($lastNews);

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

        
        // verifica se usuário segue aquele instituto
        if( in_array( $institute_name, $allowedInstitutesList) ){

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

    }
    
    echo '<div>';
    echo $changeInstitutesFollowingLink;

    foreach ($ibn_array as $institute_name => $broadcaster_array) {

        $totalNewsCount = 0;
        foreach ($broadcaster_array as $broadcaster_name => $news_array) {
            $totalNewsCount += count($ibn_array[$institute_name][$broadcaster_name]);
        }

        // institute code
        $institute_name_formated = str_replace(" ", "-", $institute_name);
        echo '<div>';
        echo '<div><h3>'.$totalNewsCount.' novas noticias de '.$institute_name_formated.'</h3></div>';
        echo '<div>';

        foreach ($broadcaster_array as $broadcaster_name => $news_array) {
            // broadcaster code
            echo '<div>';
            echo '<div>';
            echo '<h3>'.$broadcaster_name.'</h3>';
            echo '<div>';

            foreach ($news_array as $news_id => $news_properties_array) {
                // news code
                echo '<div style="box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.2), 0 3px 8px 0 rgba(0, 0, 0, 0.19);"> ';
                
                if( $news_properties_array["news_full_picture_link"] != "" ) {
                    echo '<img src="'. $news_properties_array["news_full_picture_link"] .'" alt="Card image cap">';
                }

                echo '<div>';
               
                //TITULO -> SE TIVER
                if( $news_properties_array["news_title"] != "" ) {
                    echo '<h3>' . $news_properties_array["news_title"] . '</h3>';
                }
                //DATA -> SE TIVER
                if( $news_properties_array["news_created_time"] != "" ) {
                    echo '<h4>' . $news_properties_array["news_created_time"] . '</h4>';
                }
                //SUBTITULO -> SE TIVER
                if( $news_properties_array["news_content"] != "" ) {
                    echo '<h4>' . $news_properties_array["news_content"] . '</h4>';
                }
                //MAIS CONTEUDO -> SE TIVER
                if( $news_properties_array["news_expanded_content"] != "" ) {
                    echo '<p>' . $news_properties_array["news_expanded_content"] . '</p>';
                }
                //LINKS COMPARTILHADO -> SE TIVER
                if( $news_properties_array["news_shared_link"] != "" ) {
                    echo ' <a href="' . $news_properties_array["news_shared_link"] . '">shared link</a>';
                }

                echo '</div>'; // closing NEWS BLOCK

                echo '</div>'; // closing NEWS CARD
            }

            echo '</div>'; // closing broadcast panel body
            echo '</div>'; // closing broadcast panel
            echo '</div>'; // closing broadcast COL

        }

        echo '</div>'; // closing institute  PAINEL BODY
        echo '</div>'; // closing institute  PAINEL GROUP
    }

    echo '</div>'; // closing panel-group; 
?>
</tbody> 
</table>
</div>
