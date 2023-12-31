<?php
/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
$this->title = 'Создание обращения';
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    @media(max-width: 575px){
        .tickets-create{
            border-left: none!important;
            border-right: none!important;
        }
    }
</style>

<div class="tickets-create container pt-0 mb-4 bg-light border border-dark rounded">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => 'ticket/create'
    ]);
    ?>

</div>