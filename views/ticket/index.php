<?php

use app\models\Users;
use app\models\Cities;
use app\models\Tickets;
use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\TicketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">
<div class="mx-1 mx-md-2">
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
            [
                'attribute' => 'category_id',
                'label' => 'Категория обращения',
                'value' => function($model){
                    if(isset($model->category_id)){
                        return Categories::findOne($model->category_id)->name;
                    }
                    else{
                        return $model->category_id;
                    }
                },
                'filter' => yii\helpers\ArrayHelper::map(Categories::find()->all(), 'id', 'name')
            ],
            [
                'attribute' => 'city_id',
                'label' => 'Город',
                'value' => function($model){
                    if(isset($model->city_id)){
                        return Cities::findOne($model->city_id)->name;
                    }
                    else{
                        return $model->city_id;
                    }
                },
                'filter' => yii\helpers\ArrayHelper::map(Cities::find()->all(), 'id', 'name')
            ],
            [
                'attribute' => 'user_id',
                'label' => 'Ответственный',
                'value' => function($model){
                    if(isset($model->user_id)){
                        return Users::findOne($model->user_id)->snm;
                    }
                    else{
                        return $model->user_id;
                    }
                },
                'filter' => yii\helpers\ArrayHelper::map(Users::find()->all(), 'id', 'snm')
            ],
            [
            'class' => yii\grid\ActionColumn::class,
            'urlCreator' => function ($action, Tickets $model, $key, $index, $column){
                return yii\helpers\Url::toRoute([$action, 'id' => $model->id]);
            }
            ]
        ],
        'pager' => [
            'class' => yii\widgets\LinkPager::class,
            'options' => [
                'class' => 'pagination d-flex justify-content-center',
            ],
            'linkOptions' => [
                'class' => 'page-link',
            ],
            'activePageCssClass' => 'active',
            'disabledPageCssClass' => 'page-link disabled',
            'prevPageCssClass' => 'page-item',
            'nextPageCssClass' => 'page-item',
            'disableCurrentPageButton' => true
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