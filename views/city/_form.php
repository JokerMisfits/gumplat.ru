<?php
/** @var yii\web\View $this */
/** @var app\models\Cities $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cities-form">

    <?php $form = yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'name', ['labelOptions' => ['class' => 'form-required']])->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'y', ['labelOptions' => ['class' => 'form-question-city-create-coordinate']])->textInput(); ?>

    <?= $form->field($model, 'x', ['labelOptions' => ['class' => 'form-question-city-create-coordinate']])->textInput(); ?>

    <?= $form->field($model, 'territory')->checkbox(); ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>