<?php
/** @var yii\web\View $this */
/** @var app\models\Categories $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categories-form">

    <?php $form = yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'name', ['labelOptions' => ['class' => 'form-required']])->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>