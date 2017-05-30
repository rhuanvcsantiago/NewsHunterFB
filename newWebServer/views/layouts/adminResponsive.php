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
</head>
<body>
<?php $this->beginBody() ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-3 col-lg-2">
      <nav class="navbar navbar-default navbar-fixed-side">
        <!-- normal collapsible navbar markup -->
        <?php 
            $controllerName = Yii::$app->controller->id; 
            $actionName = Yii::$app->controller->action->id ;            
        ?>
        <div class="container">
              
              <div class="navbar-header">
                <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./">NewsHunter Admin</a>
              </div>

              <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                  
                  <li <?php  if( ($controllerName=="admin") &&($actionName=="index") ) echo 'class="active"'; ?> >
                    <?= Html::a('Inicial', ['admin/index'] ) ?>
                  </li>

                  <!-- begin dropdrown -->
                  <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Cadastros
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li <?php  if( ($controllerName=="institute") &&($actionName=="index") ) echo 'class="active"'; ?> >
                            <?= Html::a('Instituto', ['institute/index'] ) ?>
                        </li>
                        <li <?php  if( ($controllerName=="institute-has-broadcaster") &&($actionName=="index") ) echo 'class="active"'; ?> >
                            <?= Html::a('Inst & Broad', ['institute-has-broadcaster/index'] ) ?>
                        </li>
                        <li <?php  if( ($controllerName=="news") &&($actionName=="index") ) echo 'class="active"'; ?> >
                            <?= Html::a('Notícias', ['news/index'] ) ?>
                        </li>
                        <li <?php  if( ($controllerName=="user") &&($actionName=="index") ) echo 'class="active"'; ?> >
                            <?= Html::a('Usuários', ['user/index'] ) ?>
                        </li>
                   </ul>
                </li>
                <!-- end dropdrown -->

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
              <ul class="nav navbar-nav navbar-right">
                <li><a href=" <?= Url::to(['site/logout']); ?> "><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
              </ul>
                
                <p class="navbar-text">
                    News Hunter - Farias Brito - todos os direitos reservados.
                </p>
              </div>
            </div>
      </nav>
    </div>
    <div class="col-sm-9 col-lg-10">
      <!-- your page content -->
      <br />
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
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

