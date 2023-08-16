<?php
/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
$this->title = 'Изменить обращение №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Обращение №' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<style>
    @media(max-width: 575px){
        .tickets-update{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="tickets-update container pt-0 mb-4 border border-dark rounded bg-light">

    <h1 class="text-wrap text-break"><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', ['model' => $model]); ?>

</div>