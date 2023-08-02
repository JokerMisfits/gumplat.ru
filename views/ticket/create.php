<?php
/** @var yii\web\View $this */
/** @var app\models\Tickets $model */
/** @var app\models\Cities $cities */
/** @var app\models\Users $users */
/** @var app\models\Categories $categories */
$this->title = 'Создание обращения';
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tickets-create container pt-0 mb-4 bg-light border border-dark rounded">

    <h1><?= yii\helpers\Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cities' => $cities,
        'users' => $users,
        'categories' => $categories,
        'action' => 'ticket/create'
    ]);
    ?>

</div>