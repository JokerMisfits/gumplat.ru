<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
        $form = ActiveForm::begin();
        $statusOptions = [
            '0' => 'Зарегистрировано',
            '1' => 'Обрабатывается',
            '2' => 'Удовлетворено',
            '3' => 'Не удовлетворено'
        ];
    ?>

    <?= $form->field($model, 'title', ['labelOptions' => ['class' => 'form-required']])->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'text', ['labelOptions' => ['class' => 'form-required']])->textarea(['rows' => 3]); ?>

    <?= $form->field($model, 'tg_user_id', ['labelOptions' => ['class' => 'form-question-ticket-create-tg-user-id']])->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true])->input('email'); ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 2]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(yii\helpers\ArrayHelper::map($categories::find()->all(), 'id', 'name'), ['prompt' => 'Выберите категорию']); ?>

    <?= $form->field($model, 'city_id')->dropDownList(yii\helpers\ArrayHelper::map($cities::find()->all(), 'id', 'name'), ['prompt' => 'Выберите город']); ?>

    <?= $form->field($model, 'user_id')->dropDownList(yii\helpers\ArrayHelper::map($users::find()->all(), 'id', 'snm'), ['prompt' => 'Выберите сотрудника']); ?>

    <?php echo $form->field($model, 'status')->dropDownList($statusOptions); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>