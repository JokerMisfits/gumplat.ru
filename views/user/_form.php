<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-form">

    <?php 
        $form = ActiveForm::begin([
            'action' => ['user/create'],
            'method' => 'post',
            'options' => [
                'autocomplete' => 'off'
            ],
            'enableClientValidation' => true,
            'enableAjaxValidation' => true,
            'validateOnBlur' => true,
            'validateOnChange' => false,
            'validateOnType' => false,
            'validateOnSubmit' => true
        ]);
    ?>

    <?= $form->field($model, 'username', ['labelOptions' => ['class' => 'form-required']])->textInput(['minlength' => 5, 'maxlength' => 32, 'class' => 'form-control', 'placeholder' => 'Введите логин']); ?>

    <?= $form->field($model, 'password', ['labelOptions' => ['class' => 'form-required']])->passwordInput(['enableAjaxValidation' => false, 'minlength' => 6, 'maxlength' => 64, 'class' => 'form-control', 'placeholder' => 'Введите пароль']); ?>

    <?= $form->field($model, 'password_repeat', ['labelOptions' => ['class' => 'form-required']])->passwordInput(['enableAjaxValidation' => false, 'maxlength' => 64, 'placeholder' => 'Введите пароль повторно', 'class' => 'form-control']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'access_token')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'tg_user_id')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'registration_date')->textInput() ?>

    <?php // echo $form->field($model, 'last_activity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>