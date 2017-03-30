<div class="row">
    <button  id="botaoConcluir" type="button" class="btn btn-primary btn-lg">CONCLUIR ALTERACOES</button>
</div>

<div class="row">


<?php
    $mysqli = new mysqli("localhost", "root", "105734", "newshunterffb");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    $sql = "SELECT institute_name, broadcaster_name, news_created_time, news_title, news_content, news_expanded_content, news_shared_link, news_full_picture_link, news_id FROM  NewsHunterFFB.ibn where is_relevant is null order by institute_name, broadcaster_name;";
    $result = $mysqli->query($sql);
    
    $institute = "";
    $broadcaster= "";

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();

        while( $row ) { 

            if( $institute != $row["institute_name"] ) {
                // ABRE E FECHA LINHA INSTITUTO
                $institute = $row["institute_name"];
                echo '<div class="row" style="background-color:GRAY; color:white; text-align:center; padding:10px;">' . $institute . '</div>';
                $instituteChanged = true;

            }

            if( $broadcaster != $row["broadcaster_name"] || $instituteChanged) {
            
                
                $broadcaster = $row["broadcaster_name"];
                
                // NOVA LINHA BROADCASTER
                if( $instituteChanged ){
                    echo '<div class="row">';
                    $instituteChanged = false;
                }
                
                // NOVA COLUNA
                echo '<div class="col-xs-4">';
                echo $broadcaster;

            }

            //ABRE CARD
            echo '<div class="card" style="border: solid 1px black; padding:5px; margin:5px;"> <!-- ABRE CARD --> ';
                //IMAGE -> SE TIVER
                if( $row["news_full_picture_link"] != "" ) {
                    echo '<img class="card-img-top img-thumbnail" src="'. $row["news_full_picture_link"] .'" alt="Card image cap">';
                }
                //CARD BLOCK
                echo '<div class="card-block">';
                    //TITULO -> SE TIVER
                    if( $row["news_title"] != "" ) {
                        echo '<h4 class="card-title">' . $row["news_title"] . '</h4>';
                    }
                    //DATA -> SE TIVER
                    if( $row["news_created_time"] != "" ) {
                        echo '<h6 class="card-subtitle mb-2 text-muted">' . $row["news_created_time"] . '</h6>';
                    }
                    //SUBTITULO -> SE TIVER
                    if( $row["news_content"] != "" ) {
                        echo '<h6 class="card-subtitle mb-2 text-muted">' . $row["news_content"] . '</h6>';
                    }
                    //MAIS CONTEUDO -> SE TIVER
                    if( $row["news_expanded_content"] != "" ) {
                        echo '<p class="card-text">' . $row["news_expanded_content"] . '</p>';
                    }
                    //LINKS COMPARTILHADO -> SE TIVER
                    if( $row["news_shared_link"] != "" ) {
                        echo ' <a href="' . $row["news_shared_link"] . '" class="card-link">shared link</a>';
                    }
                //FECHA CARD BLOCK
                echo '</div> <!-- FECHA CARD BLOCK-->';
                // BOTOES
                echo '<button type="button" class="botaoAprovar btn btn-success btn-xs ">Aprovar<pre style="display: none">'.$row["news_id"].'</pre></button>';
                echo '<button type="button" class="botaoNegar btn btn-warning btn-xs">Ignorar<pre style="display: none">'.$row["news_id"].'</pre></button>';
                echo '<button type="button" class="botaoDepois btn btn-default btn-xs">Ver depois<pre style="display: none">'.$row["news_id"].'</pre></button>';
            //FECHA CARD
            echo '</div> <!-- FECHA CARD --> ';

            $row = $result->fetch_assoc();

            if( !$row || ( $broadcaster != $row["broadcaster_name"] ) ) { 
                // FECHA COLUNA NOVA COLUNA  
                echo'</div> <!-- FECHA COL X4-->';
            } else {
                if(  $institute != $row["institute_name"]  ) {
                    // FECHA NOVA LINHA BROADCASTER
                    echo'</div> <!-- FECHA COL QUANDO BROADCASTERS IGUAL AO PROX-->';
                }
            }

            if( !$row || ( $institute != $row["institute_name"] ) ) {
                // FECHA NOVA LINHA BROADCASTER
                echo'</div> <!-- FECHA ROW BROADCASTERS -->';
            }

            

        } 
    } else {
        echo "FALHA CRITICA<BR/>";
        echo "SCRIPT ENCERRADO";
        exit;
    }

?>



<script>
    var NEWS_LIST_TO_APROVE = [];
    var NEWS_LIST_TO_IGNORE = [];

    $(".botaoAprovar").on("click", function(){

        var newsId = $(this).children().text(); 

        var index = NEWS_LIST_TO_IGNORE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_IGNORE.splice(index, 1);
        }

        NEWS_LIST_TO_APROVE.push( newsId ); 

        $(this).parent().css("background-color", "#90EE90");
    })

    $(".botaoNegar").on("click", function(){
        var newsId = $(this).children().text(); 

        var index = NEWS_LIST_TO_APROVE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_APROVE.splice(index, 1);
        }

        NEWS_LIST_TO_IGNORE.push( newsId ); 

        $(this).parent().css("background-color", "#FFA07A");
  
    })

    $(".botaoDepois").on("click", function(){
        var newsId = $(this).children().text(); 

        var index = NEWS_LIST_TO_APROVE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_APROVE.splice(index, 1);
        }

        index = NEWS_LIST_TO_IGNORE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_IGNORE.splice(index, 1);
        }

        $(this).parent().css("background-color", "white");
  
    })

    $("#botaoConcluir").on("click", function(){
        console.log( "NEWS_LIST_TO_APROVE" );
        console.log( NEWS_LIST_TO_APROVE );
        console.log( "NEWS_LIST_TO_IGNORE" );        
        console.log( NEWS_LIST_TO_IGNORE );
    })
</script>

  
 

