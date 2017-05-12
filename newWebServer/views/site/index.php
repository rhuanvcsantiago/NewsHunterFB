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
  
</style>

<div class="container-full">

  <?php if( $msg != "nada" ): ?>
      <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          <?php echo $msg ?>
          <br />
      </div>    
  <?php endif; ?>

      <div class="row">
       
        <div class="col-lg-12 text-center v-center">
          
          <h1>News Hunter</h1>
          <p class="lead">Registre seu e-mail para ficar por dentro de todas as notícias de vestibulares.</p>

          
          
          <form class="col-lg-12" id="myform" action="/index.php">
              
            <div class="input-group input-group-lg col-sm-offset-4 col-sm-4">
              <input id="field" type="text" class="center-block form-control input-lg" name="email" value="" title="Enter you email." placeholder="Enter your email address">
              <span class="input-group-btn"><button id="okButton" class="btn btn-lg btn-primary" type="button">OK</button></span>
            </div>
            <div id="message"></div>
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
  
  if( !isValidEmailAddress( $("#field").val() )){
    $("#message").empty();
    $("#message").append("<br/>EMAIL INVALIDO");
  }

});

function isValidEmailAddress(emailAddress) {
    var pattern = /[A-Z0-9._%+-]+@[A-Z0-9-]+.+.[A-Z]{2,4}/igm;
    return pattern.test(emailAddress);
};
</script>


