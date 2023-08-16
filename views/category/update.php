<?php
/** @var yii\web\View $this */
/** @var app\models\Categories $model */
$this->title = 'Изменение категории: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<style>
    @media(max-width: 575px){
        .categories-update{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="categories-update container pt-0 mb-4 border border-dark rounded bg-light">

    <h1 class="text-wrap text-break"><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]);
    ?>

</div>