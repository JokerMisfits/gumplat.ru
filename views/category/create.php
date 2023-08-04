<?php
/** @var yii\web\View $this */
/** @var app\models\Categories $model */
$this->title = 'Добавление категории';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    @media(max-width: 575px){
        .categories-create{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="categories-create container pt-0 mb-4 bg-light border border-dark rounded">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]);
    ?>

</div>