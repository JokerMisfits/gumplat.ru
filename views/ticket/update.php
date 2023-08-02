<?php
/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var app\models\Cities $cities */
/** @var app\models\Users $users */
/** @var app\models\Categories $categories */

$this->title = 'Изменить обращение №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Обращение №' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tickets-update container pt-0 mb-4 border border-dark rounded bg-light">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cities' => $cities,
        'users' => $users,
        'categories' => $categories,
        'action' => 'ticket/update'
    ]);
    ?>

</div>