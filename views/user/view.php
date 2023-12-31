<?php
/** @var yii\web\View $this */
/** @var app\models\Users $model */
$this->title = $model->snm;
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view container table-responsive pt-0 mb-4 border border-dark rounded bg-light">

    <h1 class="text-wrap text-break"><?= yii\helpers\Html::encode($this->title); ?></h1>

    <p>
        <?php 
            echo yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary my-2 mx-1']);
            if((($model->id < 10 && Yii::$app->user->identity->id === Yii::$app->params['developerUserId']) || $model->id >= 10) && $model->id !== Yii::$app->user->identity->id){
                echo yii\helpers\Html::a(\Yii::$app->authManager->checkAccess($model->id, 'user') ? 'Заблокировать учетную запись' : 'Разблокировать учетную запись', ['block', 'id' => $model->id], [
                    'class' => 'btn btn-danger my-2 mx-1',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите заблокировать учетную запись данному сотруднику?',
                        'method' => 'post'
                    ]
                ]);
                echo yii\helpers\Html::a('Сбросить пароль', ['reset', 'id' => $model->id], [
                    'class' => 'btn btn-outline-danger my-2 mx-1',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите сбросить пароль у данного сотрудника?',
                        'method' => 'post'
                    ]
                ]);
            }
            if((($model->id < 10 && Yii::$app->user->identity->id === Yii::$app->params['developerUserId']) || $model->id >= 10) && !isset($model->tg_user_id)){
                echo yii\helpers\Html::a(\Yii::$app->cache->get('tg' . $model->id) !== false ? 'Показать проверочный код' : 'Привязать telegram', ['tg-verify', 'id' => $model->id], [
                    'class' => 'btn btn-outline-primary my-2 mx-1'
                ]);
            }
        ?>
    </p>

    <?= yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'snm',
            [
                'attribute' => 'registration_date',
                'value' => function($model){
                    $dateTime = new DateTime($model->registration_date, null);
					return Yii::$app->formatter->asDatetime($dateTime, 'php:d.m.Y H:i:s');
                }
            ]
        ]
    ]);
    ?>

</div>