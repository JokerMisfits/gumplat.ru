<?php
/** @var yii\web\View $this */
/** @var app\models\DocumentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="documents-search container py-2 my-2 border border-dark rounded bg-light">

    <?php $form = yii\widgets\ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ]
    ]);
    ?>

    <?= $form->field($model, 'extension')->textInput(['class' => 'form-control', 'placeholder' => 'Пример: pdf']); ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Поиск', ['class' => 'btn btn-primary']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>