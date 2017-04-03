<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>NewsHunter admin painel </title>
    <!-- Bootstrap -->
    <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row" style="text-align:center; padding: 20px; color:#9d9d9d; background-color:#101010;">
         HEADER - NewsHunter - HEADER
    </div>
    <div class="row">
        <div class="container-fluid" >
            <div class="row" style="padding-left:15px;">
                <div id="menu" class="col-xs-2" style="padding-top:10px; padding-left:10px; min-height:400px"> 
                    <div class="list-group">
                        <a id="cadastros" href="#" class="list-group-item"> CADASTROS      </a>
                        <a id="runFetcher" href="#" class="list-group-item"> RUN FETCHER    </a>
                        <a id="manageNews" href="#" class="list-group-item"> MANAGE NEWS    </a>
                        <a id="sendMails"  href="#" class="list-group-item"> SEND MAILS     </a>
                    </div>               
                </div>
                <div id="content" class="col-xs-10" style="padding:10px; min-height:400px">                
                    PAGINA INICIAL
                    SERA CARREGADO CONTEUDO DAS OUTRAS PAGINAS VIA AJAX             
                </div>
            </div>       
        </div>
    </div>
    <div class="row" style="text-align:center; padding: 20px; color:#9d9d9d; background-color:#101010;">
                FOOTER - NewsHunter - FOOTER        
            </div>     
    <!--jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="libs/bootstrap/js/bootstrap.min.js"></script>

    <script>
        
        $("#runFetcher").on("click", function(){
           
            $("#content").html("<h4> executando fetcher... </h4>");

            var jqxhr = $.ajax( "admin/fetcher.php" )
            .done(function() {
                $("#content").append( "<h4> busca de notícias concluida! Resultados: </h4>" );
                $("#content").append( jqxhr.responseText );
            })
            .fail(function() {
                $("#content").append( "<h2> deu pau ! EITA! </h2>" );
            })
            .always(function() {
                // apaga o gif de load
                // $("#content").append( "funcao alway" );
            });
        });

        $("#cadastros").on("click", function(){
           
            $("#content").html("<h4>pegando cadastros... </h4>");

            var jqxhr = $.ajax( "admin/cadastros.php" )
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

         $("#manageNews").on("click", function(){
           
            $("#content").html("<h4>pegando cadastros... </h4>");

            var jqxhr = $.ajax( "admin/manageNews.php" )
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

        $("#sendMails").on("click", function(){
           
            $("#content").html("<h4>pegando cadastros... </h4>");

            var jqxhr = $.ajax( "admin/sendMails.php" )
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

</body>

</html>