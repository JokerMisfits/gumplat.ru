<?php
/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-search">

    <?php $form = yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ]
    ]);
    ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Поиск', ['class' => 'btn btn-primary']); ?>
        <?= yii\helpers\Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>