<?php
/** @var yii\web\View $this */
/** @var app\models\CategorySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categories-search">

    <?php $form = yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= yii\helpers\Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>