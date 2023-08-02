<?php

use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\Documents $model */

$this->title = $model->base_name . '.' . $model->extension;
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
yii\web\YiiAsset::register($this);
?>

<div class="documents-view container table-responsive pt-0 mb-4 border border-dark rounded bg-light">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <p>
        <?= yii\helpers\Html::a('Скачать', ['download-file', 'id' => $model->id], ['class' => 'btn btn-success']); ?>
        <?= yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= yii\helpers\Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить данный файл?',
                'method' => 'post'
            ]
        ]) ?>
    </p>

    <?= yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'base_name',
            'extension',
            [
                'attribute' => 'creation_date',
                'label' => 'Дата создания обращения',
                'value' => function ($model) {
                    $dateTime = new DateTime($model->creation_date, null);
                    return Yii::$app->formatter->asDatetime($dateTime, 'php:d.m.Y H:i:s');
                }
            ],
            [
                'attribute' => 'category_id',
                'label' => 'Категория',
                'value' => function($model){
                    if(isset($model->category_id)){
                        return Categories::findOne($model->category_id)->name;
                    }
                    else{
                        return $model->category_id;
                    }
                }
            ]
        ]
    ]);
    ?>

</div>