<?php
use app\models\Categories;
/** @var yii\web\View $this */
/** @var app\models\Documents $model */
/** @var yii\widgets\ActiveForm $form */
/** @var string $action */
?>

<div class="documents-form">

    <?php $form = yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php 
        if($action === 'document/create'){
            echo 'Доступные фоматы для загрузки: ' . $model->getExtensions() . '<hr class="text-primary my-2">';
            echo $form->field($model, 'file', ['labelOptions' => ['class' => 'form-required']])->fileInput(['class' => 'form-control', 'type' => 'file']);
        }
        echo $form->field($model, 'category_id', ['labelOptions' => ['class' => 'form-required']])->dropDownList(yii\helpers\ArrayHelper::map(Categories::find()->select(['id', 'name'])->groupBy('name')->all(), 'id', 'name'), ['prompt' => 'Выберите категорию', 'class' => 'form-control', 'style' => 'cursor: pointer;']);
    ?>

    <div class="form-group">
        <?= yii\helpers\Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php yii\widgets\ActiveForm::end(); ?>

</div>