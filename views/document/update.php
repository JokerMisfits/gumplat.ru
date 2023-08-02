<?php

/** @var yii\web\View $this */
/** @var app\models\Documents $model */
/** @var app\models\Categories $categories */

$this->title = 'Изменение документа: ' . $model->base_name . '.' . $model->extension;
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->base_name . '.' . $model->extension, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="documents-update container pt-0 mb-4 border border-dark rounded bg-light">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'action' => 'document/update'
    ]);
    ?>

</div>