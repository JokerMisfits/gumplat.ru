<?php
use app\models\Users;
use app\models\Cities;
use app\models\Tickets;
use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\TicketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Cities $cities */

$this->title = 'Обращения';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('https://api-maps.yandex.ru/2.1/?apikey=0296c13d-3743-4d3f-84fa-abc8f75f3562&lang=ru_RU', ['async' => 'async', $this::POS_HEAD]);
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
            $template = '{update} {delete}';
        }
        else{
            $template = '{update}';
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
                        return null;
                    }
                },
                'filter' => ['0' => 'Зарегистрировано', '1' => 'Обрабатывается', '2' => 'Удовлетворено', '3' => 'Не удовлетворено'],
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все', 'style' => 'cursor: pointer;'],
                'format' => 'raw',
                'contentOptions' => ['style' => 'text-align: center;']
            ],
            'snm',
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
                'filter' => yii\helpers\ArrayHelper::map(
					Categories::find()
                    ->select(['IF(COUNT(id) > 0, MAX(id), NULL) as id', 'MAX(name) as name'])
                    ->groupBy('name')
                    ->asArray()
                    ->all(),
					'id',
					'name'
				),
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все', 'style' => 'cursor: pointer;']
            ],
            [
                'attribute' => 'city_id',
                'label' => 'Н. П.',
                'value' => function($model){
                    if(isset($model->city_id)){
                        return Cities::findOne($model->city_id)->name;
                    }
                    else{
                        return $model->city_id;
                    }
                },
                'filter' => yii\helpers\ArrayHelper::map(Cities::find()->select(['id', 'name'])->where(['territory' => 0])->groupBy('name')->asArray()->all(), 'id', 'name') + ['Новая территория' => yii\helpers\ArrayHelper::map(Cities::find()->select(['id', 'name'])->where(['territory' => 1])->groupBy('name')->asArray()->all(), 'id', 'name')],
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все', 'style' => 'cursor: pointer;']
            ],
            // [
            //     'attribute' => 'user_id',
            //     'label' => 'Ответственный',
            //     'value' => function($model){
            //         if(isset($model->user_id)){
            //             return Users::findOne($model->user_id)->snm;
            //         }
            //         else{
            //             return $model->user_id;
            //         }
            //     },
            //     'filter' => yii\helpers\ArrayHelper::map(Users::find()->where(['or', ['id' => Yii::$app->params['systemUserId']], ['>=', 'id', 10]])->asArray()->all(), 'id', 'snm'),
            //     'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все']
            // ],
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
                    'options' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'placeholder' => 'Все', 'readonly' => true, 'style' => 'cursor: pointer;']
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
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'data-href' => \yii\helpers\Url::to(['ticket/view', 'id' => $model->id]),
                'onclick' => 'window.location.href = "' . \yii\helpers\Url::to(['ticket/view', 'id' => $model->id]) . '"'
            ];
        },
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

<?php echo '<div id="map" style="display:none; width: 100%; height: 500px;">' . $this->render('map', ['view' => $this, 'model' => $cities]) . '</div>'; //inline-block?>

<?php yii\widgets\Pjax::end(); ?>

</div>

<script>
    const tg = window.Telegram.WebApp;
    if(tg.initDataUnsafe?.user?.id) {
        tg.ready();
        tg.expand();
        tg.enableClosingConfirmation();
        tg.setHeaderColor(tg.headerColor);
    }
</script>

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