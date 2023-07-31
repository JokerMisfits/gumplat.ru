<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $from */
?>

<div class="users-form">

    <?php 
        $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'snm', ['labelOptions' => ['class' => 'form-required']])->textInput(['minlength' => 4, 'maxlength' => 255, 'class' => 'form-control', 'placeholder' => 'Введите ФИО сотрудника']); ?>

    <?php 
        if($from === 'create'){
            echo $form->field($model, 'username', ['labelOptions' => ['class' => 'form-required']])->textInput(['minlength' => 5, 'maxlength' => 32, 'class' => 'form-control', 'placeholder' => 'Введите логин']);
            echo $form->field($model, 'password', ['labelOptions' => ['class' => 'form-required']])->passwordInput(['enableAjaxValidation' => false, 'minlength' => 6, 'maxlength' => 64, 'class' => 'form-control', 'placeholder' => 'Введите пароль']);
            echo $form->field($model, 'password_repeat', ['labelOptions' => ['class' => 'form-required']])->passwordInput(['enableAjaxValidation' => false, 'maxlength' => 64, 'placeholder' => 'Введите пароль повторно', 'class' => 'form-control']);
        }
    ?>

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