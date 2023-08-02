<?php
/** @var yii\web\View $this */
/** @var app\models\Categories $model */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categories-view container table-responsive pt-0 mb-4 border border-dark rounded bg-light">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <p>
        <?= yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= yii\helpers\Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить данную категорию?',
                'method' => 'post',
            ],
        ]);
        ?>
    </p>

    <?= yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'name'
        ]
    ]);
    ?>

</div>