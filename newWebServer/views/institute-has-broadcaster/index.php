<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\InstituteHasBroadcasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Institute Has Broadcasters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="institute-has-broadcaster-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Institute Has Broadcaster', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'institute.name',
                'label' => 'Institute'
            ],
            [
                'attribute' => 'broadcaster.name',
                'label' => 'Broadcaster'
            ],
            'userName',
            'link',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
