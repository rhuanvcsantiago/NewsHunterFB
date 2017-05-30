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
    
        </div>
        
      </div> <!-- /row -->
</div>



