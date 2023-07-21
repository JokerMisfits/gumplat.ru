<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */

$this->title = 'Create Tickets';
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-create container pt-2">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
