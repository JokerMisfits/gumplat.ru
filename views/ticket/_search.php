<?php
/** @var yii\web\View $this */
/** @var app\models\TicketSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tickets-search container py-2 my-2 border border-dark rounded bg-light">

    <?php $form = yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ]
    ]);
    ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true])->label('Номер обращения'); ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 1]); ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 1]); ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>