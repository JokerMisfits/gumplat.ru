<?php

use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\CategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-index">

<div class="mx-1 mx-md-2">
    <p>
        <?= yii\helpers\Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success mt-1']); ?>
        <?= yii\helpers\Html::a('Сбросить все фильтры и сортировки', ['/categories?sort='], ['class' => 'btn btn-outline-secondary mt-1']); ?>
    </p>
</div>

<div class="table-responsive text-nowrap">
    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'ticketsCount',
                'label' => 'Количество обращений',
                'value' => function($model){
                    if($model->ticketsCount > 0){
                        return \yii\helpers\Html::a($model->ticketsCount, \yii\helpers\Url::to(['tickets/', 'TicketSearch[category_id]' => $model->id]), ['class' => 'link-primary', 'title' => 'Перейти', 'target' => '_self']);
                    }
                    return $model->ticketsCount;
                },
                'contentOptions' => ['style' => 'text-align: center;'],
                'filter' => '',
                'format' => 'raw'
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'urlCreator' => function ($action, Categories $model, $key, $index, $column) {
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

</div>