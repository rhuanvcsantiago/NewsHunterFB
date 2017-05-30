<?php
  use yii\widgets\ActiveForm;

?>
<div class="container" style="width:500px; margin-top:50px">

<?php if( isset($msgm) )  : ?>

  <?php if($msgm["type"] == "danger" ) : ?>
    <div class="alert alert-danger">
      <strong>Erro!</strong> <?= $msgm["value"] ?>
    </div>

  <?php elseif($msgm["type"] == "success" ) : ?>
    <div class="alert alert-sucess">
      <strong>Sucesso!</strong> <?= $msgm["value"] ?>
    </div>

  <?php else: ?>
    <div class="alert alert-info">
      <strong>Aviso!</strong> <?= $msgm["value"] ?>
    </div>

  <?php endif; ?>
  
<?php endif; ?>



<?php if( isset($userHasInstitutes) && isset($institutes) ) : ?>
  <?php 

    $htmlTextSeguindo = "";
    $htmlTextDisponivel = "";
    $alreadyDisplaying = [];
    $user = $userHasInstitutes[0]->user;

    foreach ($userHasInstitutes as $pos => $userHasInstitute) {
      # code...
      $alreadyDisplaying[$userHasInstitute->institute->id] = $userHasInstitute->institute->name ;
      $htmlStringCol = '<div class="checkbox">'.
                            '<label><input checked type="checkbox" value="' . $userHasInstitute->institute->id . '">' . $userHasInstitute->institute->name . '</label>'.
                        '</div>';

      $htmlTextSeguindo .= $htmlStringCol;
    } 

    foreach ($institutes as $pos => $institute) {
      # code...
      if( !isset( $alreadyDisplaying[$institute->id] ) ){
        $htmlStringCol = '<div class="checkbox">'.
                              '<label><input type="checkbox" value="' . $institute->id . '">' . $institute->name . '</label>'.
                          '</div>';

        $htmlTextDisponivel .= $htmlStringCol;
      }
    } 

    
    
    //var_dump($userHasInstitutes); 

  ?>
   
    <br/>
    <div class="row text-center">
      <div class="col-sm-12">
        <h3 >Bem vindo, <?= $user->email ?> ! </h3>
        <hr />
        <h4> Marque os institutos que gostaria de seguir: </h4>
      </div>
    </div>
    
    <div class="row text-left">
      <div class="col-sm-12">
        <div class="container-fluid">
          <br/>
          <div class="row">
            <div class="col-sm-6 text-center">
              SEGUINDO:
            </div>
            <div class="col-sm-6 text-center">
              NÃO SEGUINDO:
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 text-left" style="padding-left:50px">
              <?= $htmlTextSeguindo ?>
            </div>
            <div class="col-sm-6 text-left" style="padding-left:50px">
              <?= $htmlTextDisponivel ?>
            </div>
          </div>
          <hr />
        </div>
      </div>
    </div>
    <div class="row text-center">
      <div class="col-sm-12">
        
        <button id="buttonCreateNewUser" class="btn btn-danger" type="Submit"> Alterar Incrição </button>

        <form id="submitForm" method="GET" action="/index.php" style="display:none">
          <input type="text" name="r" value="site/edit-user-following-institutes">
          <input type="text" name="userEmail" value="<?= $user->email ?>">
          <input type="text" name="userHash" value="<?= $user->hash ?>">
        </form>

      </div>
    </div>
    <br/>

    <script>
      var selectedElements = [];

      $("#buttonCreateNewUser").on("click", function(){
        
        $(".checkbox :checked").each( function(){
          selectedElements.push( $(this).val() )
        });
        
        var text = selectedElements.join(",");

        $("#submitForm").append( '' );
        $("#submitForm").append( '<input type="text" name="institutesIdList" value="'+ text +'">' );
        $("#submitForm").submit();

        
      });

      

    </script>
  
  <?php endif; ?>
</div>
