<?php

/** @var yii\web\View $this */
/** @var app\models\Cities $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Н. П.', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cities-view container table-responsive pt-0 mb-4 border border-dark rounded bg-light">

    <h1 class="text-wrap text-break"><?= \yii\helpers\Html::encode($this->title); ?></h1>

    <p>
        <?= \yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= \yii\helpers\Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить данный Н. П.?',
                'method' => 'post'
            ]
        ]);
        ?>
    </p>

    <?= \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'x',
            'y',
            [
                'attribute' => 'territory',
                'label' => 'Новая территория',
                'value' => function($model){
                    if($model->territory === 1){
                        return 'Да';
                        
                    }
                    else{
                        return 'Нет';
                    }
                }
            ]
        ]
    ]);

    ?>

</div>