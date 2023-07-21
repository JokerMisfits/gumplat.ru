<?php


use yii\grid;
use yii\helpers;
use yii\widgets\Pjax;
use app\models\Tickets;

/** @var yii\web\View $this */
/** @var app\models\TicketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">

<div class="crudHeader">
<h1><?= yii\helpers\Html::encode($this->title) ?></h1>

<p><?= yii\helpers\Html::a('Создать обращение', ['create'], ['class' => 'btn btn-success']) ?></p>
</div>


<?php Pjax::begin(); ?>

<div class="table-responsive">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'tg_user_id',
            'status',
            'name',
            'surname',
            //'phone',
            //'answers:ntext',
            'title',
            //'text:ntext',
            //'comment:ntext',
            //'last_change',
            [
                'attribute' => 'category_id',
                'label' => 'Категория обращения'
            ],
            [
            'class' => yii\grid\ActionColumn::class,
            'urlCreator' => function ($action, Tickets $model, $key, $index, $column){
                return yii\helpers\Url::toRoute([$action, 'id' => $model->id]);
            }
            ]
        ]
    ]); 
    ?>
</div>

<?php Pjax::end(); ?>

</div>
