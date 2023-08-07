<?php

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="ticket-result">

    <?php 
        $form = yii\widgets\ActiveForm::begin(['action' => '/ticket/update?id=' . $model->id]);
    ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 3, 'value' => $model->comment])->hint('Пример:' . '<br>' . '06/08/23 Взято в работу, прозвон клиента' . '<br>' . '07/08/23 Запрос документов у клиента' . '<br>' . '24/08/23 Документы не были получены => обращение закрыто.'); ?>


    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>