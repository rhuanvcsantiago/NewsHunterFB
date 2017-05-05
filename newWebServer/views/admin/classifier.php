
<?php if( $updateResult["result"] == "success" ): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button
        <strong>SUCESSO!</strong> Notícias classificadas corretamente. 
        <br />
        <?= "ignoradas:" . json_encode( $updateResult["ignore"] )  ?>
        <?= "aprovadas:" . json_encode( $updateResult["approve"] ) ?> 
    </div>    
<?php endif; ?>    

<?php if( $updateResult["result"] == "error" ): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button
        <strong>ERRO!</strong> Algo aconteceu ao salvar no banco de dados. Entre em contato com o responsável pelo sistema.
    </div>
<?php endif; ?>

<button  id="botaoConcluir" type="button" class="btn btn-primary btn-lg">CONCLUIR ALTERACOES</button>
&nbsp&nbsp<h4 style="display:inline;"> [ <a id="labelIgnorados">0</a> ] ignorados [ <a id="labelAprovados">0</a> ] aprovados </h4>
<div id="requestResponse"></div>
<br/>

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

    //debug($ibn_array);
    
    echo '<div class="panel-group">';

    foreach ($ibn_array as $institute_name => $broadcaster_array) {

        $totalNewsCount = 0;
        foreach ($broadcaster_array as $broadcaster_name => $news_array) {
            $totalNewsCount += count($ibn_array[$institute_name][$broadcaster_name]);
        }

        // institute code
        $institute_name_formated = str_replace(" ", "-", $institute_name);
        echo '<div class="panel panel-info">';
        echo '<div class="panel-heading" data-toggle="collapse" data-target="#'.$institute_name_formated.'News"><span class="badge">'.$totalNewsCount.'</span> '.$institute_name_formated.'</div>';
        echo '<div id="'.$institute_name_formated.'News" class="panel-body collapse">';

        foreach ($broadcaster_array as $broadcaster_name => $news_array) {
            // broadcaster code
            echo '<div class="col-md-4">';
            echo '<div class="panel panel-warning">';
            echo '<div class="panel-heading">'.$broadcaster_name.'</div>';
            echo '<div class="panel-body">';

            foreach ($news_array as $news_id => $news_properties_array) {
                // news code
                echo '<div class="card" style="box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.2), 0 3px 8px 0 rgba(0, 0, 0, 0.19); padding:5px; margin:5px;"> ';
                
                if( $news_properties_array["news_full_picture_link"] != "" ) {
                    echo '<img class="card-img-top img-thumbnail" src="'. $news_properties_array["news_full_picture_link"] .'" alt="Card image cap">';
                }

                echo '<div class="card-block">';
               
                //TITULO -> SE TIVER
                if( $news_properties_array["news_title"] != "" ) {
                    echo '<h4 class="card-title">' . $news_properties_array["news_title"] . '</h4>';
                }
                //DATA -> SE TIVER
                if( $news_properties_array["news_created_time"] != "" ) {
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . $news_properties_array["news_created_time"] . '</h6>';
                }
                //SUBTITULO -> SE TIVER
                if( $news_properties_array["news_content"] != "" ) {
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . $news_properties_array["news_content"] . '</h6>';
                }
                //MAIS CONTEUDO -> SE TIVER
                if( $news_properties_array["news_expanded_content"] != "" ) {
                    echo '<p class="card-text">' . $news_properties_array["news_expanded_content"] . '</p>';
                }
                //LINKS COMPARTILHADO -> SE TIVER
                if( $news_properties_array["news_shared_link"] != "" ) {
                    echo ' <a href="' . $news_properties_array["news_shared_link"] . '" class="card-link">shared link</a>';
                }

                echo '</div>'; // closing NEWS BLOCK

                echo '<button type="button" class="botaoAprovar btn btn-success btn-xs ">Aprovar<pre style="display: none">'.$news_id.'</pre></button>';
                echo '<button type="button" class="botaoNegar btn btn-warning btn-xs">Ignorar<pre style="display: none">'.$news_id.'</pre></button>';
                echo '<button type="button" class="botaoDepois btn btn-default btn-xs">Ver depois<pre style="display: none">'.$news_id.'</pre></button>';

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

<!--
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-target="#News">
        News
    </div>
    <div id="News" class="panel-body collapse">
        <pre>
            asdasd
            asdasdasd
            asdasdasda
            asdas
        </pre>
    </div>
</div>    
-->

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

        $("#labelAprovados").text( NEWS_LIST_TO_APPROVE.length );
        $("#labelIgnorados").text( NEWS_LIST_TO_IGNORE.length );

        $(this).parent().css("background-color", "#90EE90");
    })

    $(".botaoNegar").on("click", function(){
        var newsId = $(this).children().text(); 

        var index = NEWS_LIST_TO_APPROVE.indexOf(newsId);
        
        if (index > -1) {
            NEWS_LIST_TO_APPROVE.splice(index, 1);
        }

        NEWS_LIST_TO_IGNORE.push( newsId ); 

        $("#labelAprovados").text( NEWS_LIST_TO_APPROVE.length );
        $("#labelIgnorados").text( NEWS_LIST_TO_IGNORE.length );

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

        $("#labelAprovados").text( NEWS_LIST_TO_APPROVE.length );
        $("#labelIgnorados").text( NEWS_LIST_TO_IGNORE.length );
  
    })

    $("#botaoConcluir").on("click", function(){

        var redirect = '/index.php?r=admin/classifier';

        // jquery extend function
        $.extend(
        {
            redirectPost: function(location, args)
            {
                var form = '';
                $.each( args, function( key, value ) {
                    //console.log( "chave:" + key );
                    //console.log( "valor:" + value ";
                    value = value.split('"').join("\"");
                    form += '<input type="hidden" name="'+key+'" value=\''+value+'\'>';
                });
                $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();
            }
        });

        $.redirectPost( redirect, { 
                                        approve: JSON.stringify( NEWS_LIST_TO_APPROVE ), 
                                        ignore:  JSON.stringify( NEWS_LIST_TO_IGNORE )
                                  }
                      );

    });    


</script>

