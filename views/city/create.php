<?php
/** @var yii\web\View $this */
/** @var app\models\Cities $model */
$this->title = 'Добавление Н. П.';
$this->params['breadcrumbs'][] = ['label' => 'Н. П.', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    @media(max-width: 575px){
        .cities-create{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="cities-create container pt-0 mb-4 bg-light border border-dark rounded">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]);
    ?>

</div>