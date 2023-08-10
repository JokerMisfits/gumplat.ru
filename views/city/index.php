<?php

use app\models\Cities;

/** @var yii\web\View $this */
/** @var app\models\CitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Города';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-index">

<div class="mx-1 mx-md-2">
    <p>
        <?= yii\helpers\Html::a('Добавить город', ['create'], ['class' => 'btn btn-success mt-1']); ?>
    </p>
</div>

<?php yii\widgets\Pjax::begin(); ?>

<div class="table-responsive text-nowrap">
    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'y',
                'label' => 'Широта',
                'filter' => ''
            ],
            [
                'attribute' => 'x',
                'label' => 'Долгота',
                'filter' => ''
            ],
            [
                'attribute' => 'territory',
                'label' => 'Новая территория',
                'value' => function($model){
                    if($model->territory === 0){
                        return 'Нет';
                    }
                    else{
                        return 'Да';
                    }
                },
                'filter' => [
                    0 => 'Нет',
                    1 => 'Да',
                ],
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все'],
                'contentOptions' => ['style' => 'text-align: center;']
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'urlCreator' => function ($action, Cities $model, $key, $index, $column) {
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