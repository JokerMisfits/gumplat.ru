<?php

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var app\models\Cities $cities */
/** @var app\models\Users $users */
/** @var app\models\Categories $categories */
/** @var yii\widgets\ActiveForm $form */
/** @var array|string $action */

?>

<div class="tickets-form">

    <?php 
        $form = yii\widgets\ActiveForm::begin();
        if($action === 'ticket/create'){
            $statusOptions = [
                '0' => 'Зарегистрировано',
                '1' => 'Обрабатывается'
            ];
        }
        else{
            $statusOptions = [
                '0' => 'Зарегистрировано',
                '1' => 'Обрабатывается',
                '2' => 'Удовлетворено',
                '3' => 'Не удовлетворено'
            ];
        }
    ?>

    <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'form-required']])->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'text', ['labelOptions' => ['class' => 'form-required']])->textarea(['rows' => 3]); ?>

    <?php // echo $form->field($model, 'tg_user_id', ['labelOptions' => ['class' => 'form-question-ticket-create-tg-user-id']])->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'minlength' => 5])->input('email'); ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 2])->hint('Пример:' . '<br>' . '06/08/23 Взято в работу, прозвон клиента' . '<br>' . '07/08/23 Запрос документов у клиента' . '<br>' . '24/08/23 Документы не были получены => обращение закрыто.'); ?>

    <?= $form->field($model, 'category_id')->dropDownList(yii\helpers\ArrayHelper::map($categories::find()->all(), 'id', 'name'), ['prompt' => 'Выберите категорию']); ?>

    <?= $form->field($model, 'city_id')->dropDownList(yii\helpers\ArrayHelper::map($cities::find()->where(['territory' => 0])->all(), 'id', 'name') + ['Новая территория' => yii\helpers\ArrayHelper::map($cities::find()->where(['territory' => 1])->all(), 'id', 'name')], ['prompt' => 'Выберите город']); ?>

    <?= $form->field($model, 'user_id')->dropDownList(yii\helpers\ArrayHelper::map($users::find()->where(['or', ['id' => Yii::$app->params['systemUserId']], ['>=', 'id', 10]])->all(), 'id', 'snm'), ['prompt' => 'Выберите сотрудника']); ?>

    <?= $form->field($model, 'status')->dropDownList($statusOptions); ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>