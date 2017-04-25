<div class="row">
    <button  id="botaoConcluir" type="button" class="btn btn-primary btn-lg">CONCLUIR ALTERACOES</button>
    <div id="salvarResult"></id>
    <script>
         $("#botaoConcluir").on("click", function(){
           
            var jqxhr = $.post( "manageNews.php", { update: { ignore: NEWS_LIST_TO_IGNORE , approve: NEWS_LIST_TO_APPROVE } } )
            .done(function() {
                $("#content").html( jqxhr.responseText );
            })
            .fail(function() {
                $("#content").append( "<h2> deu pau ! EITA! </h2>" );
            })
            .always(function() {
                // apaga o gif de load
                // $("#content").append( "funcao alway" );
            }); 
        });

    </script>
</div>

<div class="row">
<div class="container">



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

    

    // UPLOAD DATABASE WITH IGNORED OR APROVED NEWS
    if( isset( $_POST["update"] ) ){
        //debug( $_POST["update"] );
        $con = new mysqli("localhost", "root", "105734", "newshunterffb");

        if ($con->connect_errno) {
            echo "Failed to connect to MySQL: (" . $con->connect_errno . ") " . $con->connect_error;
        }
        $query = "START TRANSACTION;\nUSE NewsHunterFFB;\n";

        if( isset( $_POST["update"]["ignore"] ) ){
            foreach ($_POST["update"]["ignore"] as $index => $news_id) {
                $query .= "UPDATE Institute_has_Broadcaster_has_News SET is_relevant = FALSE WHERE news_id=". $news_id . ";\n";
            }
        }

        if( isset( $_POST["update"]["approve"] ) ){
            foreach ($_POST["update"]["approve"] as $index => $news_id) {     
                $query .= "UPDATE Institute_has_Broadcaster_has_News SET is_relevant = TRUE WHERE news_id=". $news_id . ";\n";
            }
        }

        $query .= "COMMIT;";

        if ($con->multi_query($query) === TRUE) { 
            echo "mudanças salvas, pagina atualizada;";
            echo "<br/>";
        } else {
            echo "Error: " . $query . "<br>" . $con->error;
        }  

        //debug($query);
        sleep(1);
        //echo "data para update: " . $_POST["update"];
    }

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
        $count = 0;

        while( $row ) { 

            if( $institute != $row["institute_name"] ) {
                // ABRE E FECHA LINHA INSTITUTO
                $institute = $row["institute_name"];
                echo '<div class="row " data-toggle="collapse" data-target="#'.$institute.'" style="background-color:GRAY; color:white; text-align:center; padding:10px;">' . $institute . '<span class="badge">qtd_noticias</span></div>';
                $instituteChanged = true;

            }

            if( $broadcaster != $row["broadcaster_name"] || $instituteChanged) {
            
                
                $broadcaster = $row["broadcaster_name"];
                
                // NOVA LINHA BROADCASTER
                if( $instituteChanged ){
                    echo '<div class="row collapse" id="'.$institute.'">';
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
        echo "Nenhum resultado encontrado ou falha ao buscar.<BR/>";
        echo "SCRIPT ENCERRADO.";
        exit;
    }

?>
</div>
<script>
    var NEWS_LIST_TO_APPROVE = [];
    var NEWS_LIST_TO_IGNORE = [];

    $(".botaoAprovar").on("click", function(){

        var newsId = $(this).children().text(); 

        var index = NEWS_LIST_TO_IGNORE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_IGNORE.splice(index, 1);
        }

        NEWS_LIST_TO_APPROVE.push( newsId ); 

        $(this).parent().css("background-color", "#90EE90");
    })

    $(".botaoNegar").on("click", function(){
        var newsId = $(this).children().text(); 

        var index = NEWS_LIST_TO_APPROVE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_APPROVE.splice(index, 1);
        }

        NEWS_LIST_TO_IGNORE.push( newsId ); 

        $(this).parent().css("background-color", "#FFA07A");
  
    })

    $(".botaoDepois").on("click", function(){
        var newsId = $(this).children().text(); 

        var index = NEWS_LIST_TO_APPROVE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_APPROVE.splice(index, 1);
        }

        index = NEWS_LIST_TO_IGNORE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_IGNORE.splice(index, 1);
        }

        $(this).parent().css("background-color", "white");
  
    })

    $("#botaoConcluir").on("click", function(){
        console.log( JSON.stringify({aprove: NEWS_LIST_TO_APPROVE, ignore: NEWS_LIST_TO_IGNORE  }) );
        
    })
</script>

  
 

