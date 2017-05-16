<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;


AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <title>Admin Painel</title>
  <meta charset="<?= Yii::$app->charset ?>">
  <!-- <meta charset="utf-8"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <?php $this->head() ?>
  <?= Html::csrfMetaTags() ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {min-height: 500px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
      
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }
  </style>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="navbar navbar-default">
        <h3>NewsHunter - Painel do administrador</h3>
    </div>

    <div class="container-fluid">
        <div class="row content">
            <div class="col-sm-2 sidenav">
                <br/ >
                
                <ul class="nav nav-pills nav-stacked">
                    <?php 
                    
                        $controllerName = Yii::$app->controller->id; 
                        $actionName = Yii::$app->controller->action->id ;
                    
                    ?>
                    <li <?php  if( ($controllerName=="admin") &&($actionName=="index") ) echo 'class="active"'; ?> >
                        <?= Html::a('Inicial', ['admin/index'] ) ?>
                    </li>
                    <li <?php  if( ($controllerName!="admin"&&$actionName=="index") || $actionName=="cadastros" ) echo 'class="active"'; ?>>
                        <?= Html::a('Cadastros', ['admin/cadastros'] ) ?>
                        <ul id="cadastroSubmenu">
                            <li>
                                <?= Html::a('Instituto', ['institute/index'] ) ?>
                            </li>
                            <li>
                                <?= Html::a('Inst & Broad', ['institute-has-broadcaster/index'] ) ?>
                            </li>
                            <li>
                                <?= Html::a('Notícias', ['news/index'] ) ?>
                            </li>
                            <li>
                                <?= Html::a('Usuários', ['user/index'] ) ?>
                            </li>
                        </ul>
                    </li>    
                    <li <?php  if( ($controllerName=="admin")&&($actionName=="fetcher") ) echo 'class="active"'; ?>>
                        <?= Html::a('Buscador', ['admin/fetcher'] ) ?>
                    </li>
                    <li <?php  if( ($controllerName=="admin")&&($actionName=="classifier") ) echo 'class="active"'; ?>>
                        <?= Html::a('Classificador', ['admin/classifier'] ) ?>
                    </li>
                    <li <?php  if( ($controllerName=="admin")&&($actionName=="sendemails") ) echo 'class="active"'; ?>>
                        <?= Html::a('Enviar Emails', ['admin/sendemails'] ) ?>
                    </li>
                </ul>
                <br>
            </div>
            <div class="col-sm-10"> 
                <div class="container-fluid">
                    <?= Breadcrumbs::widget([
                        'homeLink' => [ 
                            'label' => Yii::t('yii', 'Admin Painel'),
                            'url' => Url::to("index?r=admin"),
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?= $content ?>
                </div>       
            </div>  
        </div>
    </div>

    <footer class="container-fluid">
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

