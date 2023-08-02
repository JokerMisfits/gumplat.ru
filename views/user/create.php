<?php
/** @var yii\web\View $this */
/** @var app\models\Users $model */
$this->title = 'Добавить сотрудника';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-create container pt-0 mb-4 border border-dark rounded bg-light">
    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'from' => 'create'
    ]) ?>
</div>