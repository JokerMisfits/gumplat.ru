<?php
/** @var yii\web\View $this */
/** @var app\models\Documents $model */
$this->title = 'Добавление документа';
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    @media(max-width: 575px){
        .documents-create{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="documents-create container pt-0 mb-4 bg-light border border-dark rounded">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => 'document/create'
    ]);
    ?>

</div>