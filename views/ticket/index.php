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
        <button id="ticket-search-button" class="btn btn-primary mt-1" onclick="showSearch()">Показать расширенный поиск</button>
        <?= yii\helpers\Html::a('Сбросить все фильтры и сортировки', ['/tickets?sort='], ['class' => 'btn btn-outline-secondary mt-1']); ?>
    </p>
</div>

    <?php yii\widgets\Pjax::begin(); ?>

    <?php echo '<div id="ticket-search" style="display: none;">' . $this->render('_search', ['model' => $searchModel]) . '</div>'; ?>

<div class="table-responsive text-nowrap">
    <?php 
        if(Yii::$app->user->can('admin')){
            $template = '{view} {update} {delete}';
        }
        else{
            $template = '{view} {update}';
        }
        echo yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'status',
                'label' => 'Статус обращения',
                'value' => function($model){
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
                'filter' => yii\helpers\ArrayHelper::map(Categories::find()->all(), 'id', 'name'),
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все']
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
                'filter' => yii\helpers\ArrayHelper::map(Cities::find()->where(['territory' => 0])->all(), 'id', 'name') + ['Новая территория' => yii\helpers\ArrayHelper::map(Cities::find()->where(['territory' => 1])->all(), 'id', 'name')],
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все']
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
                'filter' => yii\helpers\ArrayHelper::map(Users::find()->where(['or', ['id' => Yii::$app->params['systemUserId']], ['>=', 'id', 10]])->all(), 'id', 'snm'),
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все']
            ],
            [
                'attribute' => 'creation_date',
                'label' => 'Дата обращения',
                'value' => function ($model) {
                    $dateTime = new DateTime($model->creation_date, null);
                    return Yii::$app->formatter->asDatetime($dateTime, 'php:d.m.Y H:i:s');
                },
                'filter' => yii\jui\DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'creation_date',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'placeholder' => 'Все']
                ]),
            ],
            [
            'class' => yii\grid\ActionColumn::class,
            'template' => $template,
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
        let button = document.getElementById('ticket-search-button');
        if(form.style.display === 'none'){
            form.style.display = 'block';
            button.innerText = 'Скрыть расширенный поиск';
        }
        else{
            form.style.display = 'none';
            button.innerText = 'Показать расширенный поиск';
        }
    }
</script>