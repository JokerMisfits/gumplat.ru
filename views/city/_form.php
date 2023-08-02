<?php
/** @var yii\web\View $this */
/** @var app\models\Cities $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cities-form">

    <?php $form = yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'x')->textInput(); ?>

    <?= $form->field($model, 'y')->textInput(); ?>

    <?= $form->field($model, 'territory')->checkbox(); ?> 

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>