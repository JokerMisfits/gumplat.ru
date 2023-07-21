<?php

use yii\grid;
use yii\helpers;
use yii\widgets\Pjax;
use app\models\Users;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= yii\helpers\Html::encode($this->title) ?></h1>

    <p><?= yii\helpers\Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'username',
            'name',
            'surname',
            //'password',
            //'auth_key',
            //'access_token',
            //'tg_user_id',
            //'registration_date',
            //'last_activity',
            [
                'class' => yii\grid\ActionColumn::class,
                'urlCreator' => function ($action, Users $model, $key, $index, $column){
                    return yii\helpers\Url::toRoute([$action, 'id' => $model->id]);
                 }
            ]
        ]
    ]); ?>

    <?php Pjax::end(); ?>

</div>
