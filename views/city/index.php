<?php

use app\models\Cities;

/** @var yii\web\View $this */
/** @var app\models\CitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Города';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-index">

    <h1><?= \yii\helpers\Html::encode($this->title); ?></h1>

    <p>
        <?= \yii\helpers\Html::a('Добавить город', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?php \yii\widgets\Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'x',
            'y',
            [
                'class' => \yii\grid\ActionColumn::class,
                'urlCreator' => function ($action, Cities $model, $key, $index, $column) {
                    return \yii\helpers\Url::toRoute([$action, 'id' => $model->id]);
                 }
            ]
        ]
    ]); 

    ?>

    <?php \yii\widgets\Pjax::end(); ?>

</div>