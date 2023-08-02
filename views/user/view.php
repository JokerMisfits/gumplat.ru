<?php
/** @var yii\web\View $this */
/** @var app\models\Users $model */
$this->title = $model->snm;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view container table-responsive pt-0 mb-4 border border-dark rounded bg-light">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <p>
        <?= yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
    </p>

    <?= yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'snm',
            'registration_date'
        ]
    ]);
    ?>

</div>