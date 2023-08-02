<?php
/** @var yii\web\View $this */
/** @var app\models\Cities $model */
$this->title = 'Добавление города';
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-create container pt-0 mb-4 bg-light border border-dark rounded">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]);
    ?>

</div>