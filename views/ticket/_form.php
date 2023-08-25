<?php
use app\models\Users;
use app\models\Cities;
use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $action */

$users = [];
$users = Users::find()->select(['id', 'snm'])->rightJoin('auth_assignment', 'users.id = auth_assignment.user_id')->where(['>=', 'id', 10])->orderBy(['id' => SORT_ASC])->asArray()->all();
$users[] = Users::find()->select(['id', 'snm'])->where(['id' => Yii::$app->params['systemUserId']])->asArray()->orderBy(['id' => SORT_ASC])->one();
sort($users);
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

    <?= $form->field($model, 'text', ['labelOptions' => ['class' => 'form-required']])->textarea(['rows' => 4]); ?>

    <?= $form->field($model, 'snm')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'minlength' => 5])->input('email'); ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 2])->hint('Пример:' . '<br>' . '06/08/23 Взято в работу, прозвон клиента' . '<br>' . '07/08/23 Запрос документов у клиента' . '<br>' . '24/08/23 Документы не были получены => обращение закрыто.'); ?>

    <?= $form->field($model, 'category_id')->dropDownList(yii\helpers\ArrayHelper::map(Categories::find()->select(['id', 'name'])->groupBy('name')->all(), 'id', 'name'), ['prompt' => 'Выберите категорию', 'style' => 'cursor: pointer;']); ?>

    <?= $form->field($model, 'city_id')->dropDownList(yii\helpers\ArrayHelper::map(Cities::find()->select(['id', 'name'])->where(['territory' => 0])->groupBy('name')->all(), 'id', 'name') + ['Новая территория' => yii\helpers\ArrayHelper::map(Cities::find()->select(['id', 'name'])->where(['territory' => 1])->groupBy('name')->all(), 'id', 'name')], ['prompt' => 'Выберите Н. П.', 'style' => 'cursor: pointer;'])->label('Н. П.'); ?>

    <!-- <?= $form->field($model, 'user_id')->dropDownList(yii\helpers\ArrayHelper::map($users, 'id', 'snm'), ['prompt' => 'Выберите сотрудника']); ?> -->

    <?= $form->field($model, 'status')->dropDownList($statusOptions, ['style' => 'cursor: pointer;']); ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>