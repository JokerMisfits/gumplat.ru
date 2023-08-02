<?php
/** @var yii\web\View $this */
/** @var app\models\CitySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cities-search">

    <?php $form = yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ]
    ]);
    ?>

    <?= $form->field($model, 'id'); ?>

    <?= $form->field($model, 'name'); ?>

    <?= $form->field($model, 'x'); ?>

    <?= $form->field($model, 'y'); ?>

    <?= $form->field($model, 'territory'); ?> 

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Поиск', ['class' => 'btn btn-primary']); ?>
        <?= yii\helpers\Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>