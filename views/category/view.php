<?php
/** @var yii\web\View $this */
/** @var app\models\Categories $model */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="categories-view container table-responsive pt-0 mb-4 border border-dark rounded bg-light">

    <h1 class="text-wrap text-break"><?= yii\helpers\Html::encode($this->title); ?></h1>

    <p>
        <?= yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= yii\helpers\Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить данную категорию?',
                'method' => 'post'
            ]
        ]);
        ?>
    </p>

    <?= yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'ticketsCount',
                'label' => 'Количество обращений',
                'value' => function($model){
                    $count = count($model->tickets);
                    if($count > 0){
                        return \yii\helpers\Html::a($count, \yii\helpers\Url::to(['tickets/', 'TicketSearch[category_id]' => $model->id]), ['class' => 'link-primary', 'title' => 'Перейти']);
                    }
                    return $count;
                },
                'format' => 'raw'
            ]
        ]
    ]);
    ?>

</div>