<?php
/** @var yii\web\View $this */
/** @var app\models\Users $model */
$this->title = 'Изменить сотрудника: ' . $model->snm;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->snm, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<style>
    @media(max-width: 575px){
        .users-update{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="users-update container pt-0 mb-4 border border-dark rounded bg-light">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'from' => 'update'
    ]);
    ?>

</div>