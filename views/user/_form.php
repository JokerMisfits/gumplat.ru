<?php
/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $from */
?>

<div class="users-form">

    <?php $form = yii\widgets\ActiveForm::begin(); ?>

    <?= $form->field($model, 'snm', ['labelOptions' => ['class' => 'form-required']])->textInput(['minlength' => 4, 'maxlength' => 255, 'class' => 'form-control', 'placeholder' => 'Введите ФИО сотрудника']); ?>

    <?php 
        if($from === 'create'){
            echo $form->field($model, 'username', ['labelOptions' => ['class' => 'form-required']])->textInput(['minlength' => 5, 'maxlength' => 32, 'class' => 'form-control', 'placeholder' => 'Введите логин']);
            echo $form->field($model, 'password', ['labelOptions' => ['class' => 'form-required']])->passwordInput(['enableAjaxValidation' => false, 'minlength' => 6, 'maxlength' => 64, 'class' => 'form-control', 'placeholder' => 'Введите пароль']);
            echo $form->field($model, 'password_repeat', ['labelOptions' => ['class' => 'form-required']])->passwordInput(['enableAjaxValidation' => false, 'maxlength' => 64, 'placeholder' => 'Введите пароль повторно', 'class' => 'form-control']);
        }
    ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>