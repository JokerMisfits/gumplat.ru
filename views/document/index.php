<?php

use app\models\Documents;
use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\DocumentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $folderSize */

$this->title = 'Документы';
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', [
    'crossorigin' => 'anonymous',
    'position' => $this::POS_HEAD,
]);
$this->params['breadcrumbs'][] = $this->title;
$progress = round((($folderSize * 100) / Yii::$app->params['maxFileStorageSize']), 2);
?>

<div class="mt-2 mx-1 mx-md-2 text-dark">Файловое хранилище заполнено на <?= $progress; ?>%</div>
<div class="progress mb-3 mx-1 mx-md-2" style="height: 22px;">
    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?= $progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress; ?>%"></div>
</div>

<div class="documents-index">
<div class="mx-1 mx-md-2">
    <p>
        <?= yii\helpers\Html::a('Добавить документ', ['create'], ['class' => 'btn btn-success mt-1']); ?>
        <?= yii\helpers\Html::a('Сбросить все фильтры и сортировки', ['/documents?sort='], ['class' => 'btn btn-outline-secondary mt-1']); ?>
    </p>
</div>

<div class="table-responsive text-nowrap">
    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'base_name',
            'extension',
            [
                'attribute' => 'category_id',
                'label' => 'Категория документа',
                'value' => function($model){
                    if(isset($model->category_id)){
                        return Categories::findOne($model->category_id)->name;
                    }
                    else{
                        return $model->category_id;
                    }
                },
                'filter' => yii\helpers\ArrayHelper::map(Categories::find()->select(['id', 'name'])->groupBy('name')->all(), 'id', 'name'),
                'filterInputOptions' => ['class' => 'form-control selectpicker', 'data-style' => 'btn-primary', 'prompt' => 'Все', 'style' => 'cursor: pointer;']
            ],
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{view} {update} {delete} {download}',
                'buttons' => [
                    'download' => function ($url, $model, $key) {
                        return yii\helpers\Html::a('', ['download-file', 'id' => $model->id], ['class' => 'fas fa-file-export', 'title' => 'Скачать']);
                    }
                ],
                'urlCreator' => function ($action, Documents $model, $key, $index, $column) {
                    return yii\helpers\Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
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