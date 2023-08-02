<?php

use app\models\Users;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

<div class="mx-1 mx-md-2">
    <p>
        <?= yii\helpers\Html::a('Добавить сотрудника', ['create'], ['class' => 'btn btn-success mt-1']); ?>
    </p>
</div>

    <?php yii\widgets\Pjax::begin(); ?>

    <?= yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'snm',
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{view} {update}',
                'urlCreator' => function ($action, Users $model, $key, $index, $column){
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

    <?php yii\widgets\Pjax::end(); ?>

</div>