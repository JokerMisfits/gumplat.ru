<?php

/** @var yii\web\View $this */
/** @var app\models\Cities $model */

$this->title = 'Изменение Н. П.: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Н. П.', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<style>
    @media(max-width: 575px){
        .cities-update{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="cities-update container pt-0 mb-4 border border-dark rounded bg-light">

    <h1 class="text-wrap text-break"><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]);
    ?>

</div>