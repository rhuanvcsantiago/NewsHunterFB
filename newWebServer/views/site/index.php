<style>
@import url(http://fonts.googleapis.com/css?family=Antic+Slab);

html,body {
  height:100%;
}

h1 {
  font-family: 'Antic Slab', serif;
  font-size:80px;
  color:#DDCCEE;
}

.lead {
	color:#DDCCEE;
}


/* Custom container */
.container-full {
  margin: 0 auto;
  width: 100%;
  min-height:100%;
  background-color:#110022;
  color:#eee;
  overflow:hidden;
}

.container-full a {
  color:#efefef;
  text-decoration:none;
}

.v-center {
  margin-top:7%;
}

#institutesListWrapper{
  width:416px;
  background-color: white;
  opacity: 0.9;
  color: black;
  padding-left: 50px;
  padding-right: 50px;
  display: none;
} 

#institutesList{
  width:640px;
} 

</style>



<div class="container-full">
      <div class="row">
        <div class="col-lg-12 text-center v-center">
          
          <h1>News Hunter</h1>
          <p class="lead">Registre seu e-mail para ficar por dentro de todas as notícias de vestibulares.</p>
          <?php if( isset($msg["type"]) && isset($msg["value"]) )  : ?>

            <?php if($msg["type"] == "danger" ) : ?>
              <div class="alert alert-danger">
                <strong>Erro!</strong> <?= $msg["value"] ?>
              </div>

            <?php elseif($msg["type"] == "success" ) : ?>
              <div class="alert alert-success">
                <strong>Sucesso!</strong> <?= $msg["value"] ?>
              </div>

            <?php else: ?>
              <div class="alert alert-info">
                <strong>Aviso!</strong> <?= $msg["value"] ?>
              </div>

            <?php endif; ?>
            
          <?php endif; ?>
          <form class="col-lg-12" id="myform" action="/index.php">
              
            <div class="input-group input-group-lg col-sm-offset-4 col-sm-4">
              <input id="field" type="text" class="center-block form-control input-lg" name="email" value="" title="Enter you email." placeholder="Enter your email address">
              <span class="input-group-btn"><button id="okButton" class="btn btn-lg btn-primary" type="button">OK</button></span>
            </div>
            <div id="message"></div>
            <!--<div id="institutesList" style="display: none;"> -->
            <br />
            
              <?php 

                $htmlStringCol01 = "";
                $htmlStringCol02 = "";
                $htmlStringCol03 = "";

                if ( isset($institutes) ){
                
                  $col_controller = 1;

                  foreach ($institutes as $pos => $institute) {
                                      
                    $htmlStringCol = '<div class="checkbox">'.
                                        '<label><input type="checkbox" checked value="' . $institute->id . '">' . $institute->name . '</label>'.
                                    '</div>';

                    if( $col_controller == 1){
                      $htmlStringCol01 .= $htmlStringCol;
                    }  

                    if( $col_controller == 2){
                      $htmlStringCol02 .= $htmlStringCol;
                    }  

                    if( $col_controller == 3){
                      $htmlStringCol03 .= $htmlStringCol;
                      $col_controller = 0;
                    }

                    $col_controller++;

                  }
                }
              ?> 

            <div id="institutesListWrapper" class="container"> 
              <br/>
              <div class="row text-left">
                <div class="col-sm-4" > 
                  <?= $htmlStringCol01 ?>
                </div>
                <div class="col-sm-4" > 
                  <?= $htmlStringCol02 ?>
                </div>
                <div class="col-sm-4" > 
                  <?= $htmlStringCol03 ?>
                </div>
              </div>
              <br/>
              <div class="row text-center">
                <div class="col-sm-12">
                  <button id="buttonCreateNewUser" class="btn btn-danger" type="button"> Enviar </button>
                </div>
              </div>
              <br/>
            </div>

            <br/>
            <button class="btn btn-lg btn-danger" type="button">   &nbsp Registrar com Google &nbsp</button>
            
            <br/>
            <br/>
            <button class="btn btn-lg btn-primary" type="button"> Registrar com Facebook </button>
            
          </form>
        </div>
        
      </div> <!-- /row -->
</div>


<script>

$( "#okButton" ).click(function(){
  
  var email = $("#field").val();

  if( validateEmail(email) ){
    $("#message").empty();
    $("#message").append("<br/>QUAIS INSTITUTOS GOSTARIA DE SEGUIR?");
    $("#institutesListWrapper").slideDown();
  } else {
    $("#message").empty();
    $("#message").append("<br/>EMAIL INVALIDO");
    $("#institutesListWrapper").slideUp();
  }

});

function validateEmail(mail)   
{  
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))  
  {  
    return (true)  
  }  
    return (false)  
}  

var selectedElements = [];

$("#buttonCreateNewUser").on("click", function(){


        var redirect = '/index.php?r=site/index';
        
        $("#institutesListWrapper .checkbox :checked").each( function(){
          selectedElements.push( $(this).val() )
        });

        console.log($("#field").val());
        console.log(selectedElements);

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
                                        email:   JSON.stringify( $("#field").val() ), 
                                        institutesIdList:  JSON.stringify( selectedElements ),
                                        _csrf: <?= '"'. Yii::$app->request->getCsrfToken() .'"' ?>
                                  }
                      );

    });
</script>


