<?php
/** @var yii\web\View $this */
/** @var app\models\Users $model */
$this->title = 'Добавить сотрудника';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    @media(max-width: 575px){
        .users-create{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="users-create container pt-0 mb-4 border border-dark rounded bg-light">
    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'from' => 'create'
    ]);
    ?>
</div>