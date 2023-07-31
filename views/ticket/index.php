<?php

use app\models\Tickets;

/** @var yii\web\View $this */
/** @var app\models\TicketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">

<div class="mx-1 mx-md-2">
<h1><?= yii\helpers\Html::encode($this->title); ?></h1>

<p>
    <?= yii\helpers\Html::a('Создать обращение', ['create'], ['class' => 'btn btn-success mt-1']); ?>
    <button class="btn btn-primary mt-1" onclick="showSearch()">Расширенный поиск</button>
    <?= yii\helpers\Html::a('Сбросить поиск', ['/tickets'], ['class' => 'btn btn-outline-secondary mt-1']); ?>
</p>
</div>

<?php yii\widgets\Pjax::begin(); ?>

<?php echo '<div id="ticket-search" style="display: none;">' . $this->render('_search', ['model' => $searchModel]) . '</div>'; ?>

<div class="table-responsive text-nowrap">
    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'status',
                'label' => 'Статус обращения',
                'value' => function ($model) {
                    if($model->status == 0){
                        return 'Зарегистрировано';
                    }
                    elseif($model->status == 1){
                        return 'Обрабатывается';
                    }
                    elseif($model->status == 2){
                        return 'Удовлетворено';
                    }
                    elseif($model->status == 3){
                        return 'Не удовлетворено';
                    }
                    else{
                        return '<span class="not-set">(не задано)</span>';
                    }
                },
                'filter' => ['0' => 'Зарегистрировано', '1' => 'Обрабатывается', '2' => 'Удовлетворено', '3' => 'Не удовлетворено'],
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все'],
                'format' => 'raw',
                'contentOptions' => ['style' => 'text-align: center;']
            ],
            'name',
            'surname',
            'email:email', 
            //'comment:ntext',
            [
                'attribute' => 'category_id',
                'label' => 'Категория обращения'
            ],
            [
                'attribute' => 'city_id',
                'label' => 'Город'
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Ответственный'
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

<?php yii\widgets\Pjax::end(); ?>

</div>

<script>
    function showSearch(){
        let form = document.getElementById('ticket-search');
        if(form.style.display === 'none'){
            form.style.display = 'block';
        }
        else{
            form.style.display = 'none';
        }
    }
</script>