<?php

use app\models\Users;
use app\models\Cities;
use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */

$this->title = 'Обращение №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
yii\web\YiiAsset::register($this);
?>

<style>
    th{
        white-space: nowrap!important;
        vertical-align: middle!important;
    }
</style>

<!-- Modal -->
<div class="modal fade text-light" id="Modal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content bg-dark">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Заголовок</h1>
        <button type="button" class="btn btn-danger btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="ModalContent">Текст</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

<div class="tickets-view row p-0 mx-0 mb-4 bg-light border-top border-bottom border-dark">
    <div class="col-10 table-responsive border-end border-dark" style="padding: 0 0 0 2px;">
        <h1 class="text-start"><?= 'Обращение №' . yii\helpers\Html::encode($model->id); ?></h1>
        <p>
            <?= yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mt-1']); ?>
            <?= yii\helpers\Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger mt-1',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить данное обращение?',
                    'method' => 'post',
                ]
            ]);
            ?>
        </p>
        <?= yii\widgets\DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'title',
                    'label' => 'Заголовок обращения',
                    'contentOptions' => ['id' => 'ticket-view-title'],
                    'value' => function($model){
                        $js = <<<JS
                            setModalHeader("$model->title");
                        JS;
                        $this->registerJs($js);
                        if(strlen($model->title) > 16){
                            $js = <<<JS
                            showTextMore("$model->text", 'title');
                        JS;
                        return $this->registerJs($js);
                        }
                        return $model->title;
                    }
                ],
                [
                    'attribute' => 'name',
                    'label' => 'Имя',
                    'value' => function($model){
                        if(!isset($model->name) || $model->name === ''){
                            return null;
                        }
                        else{
                            return $model->name;
                        }
                    }
                ],
                [
                    'attribute' => 'surname',
                    'label' => 'Фамилия',
                    'value' => function($model){
                        if(!isset($model->surname) || $model->surname === ''){
                            return null;
                        }
                        else{
                            return $model->surname;
                        }
                    }
                ],
                [
                    'attribute' => 'phone',
                    'label' => 'Номер телефона',
                    'value' => function($model){
                        if(!isset($model->phone) || $model->phone === ''){
                            return null;
                        }
                        else{
                            return '<a href="tel:' . $model->phone . '">' . $model->phone . '</a>';
                        }
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'email',
                    'label' => 'Почта',
                    'value' => function($model){
                        if(!isset($model->email) || $model->email === ''){
                            return null;
                        }
                        else{
                           return $model->email;
                        }
                    },
                    'format' => 'email'
                ],
                [
                    'attribute' => 'text',
                    'label' => 'Текст обращения',
                    'contentOptions' => ['id' => 'ticket-view-text'],
                    'value' => function($model){
                        if(strlen($model->text) > 16){
                            $js = <<<JS
                                showTextMore("$model->text", 'text');
                            JS;
                            return $this->registerJs($js);
                        }
                        return $model->text;
                    }
                ],
                'tg_user_id',
                [
                    'attribute' => 'status',
                    'label' => 'Статус обращения',
                    'value' => function($model){
                        if($model->status == 0){
                            return 'Зарегистрировано';
                        }
                        elseif($model->status == 1){
                            return 'Обрабатывается';
                        }
                        elseif($model->status == 2){
                            return 'Удовлетворено';
                        }
                        elseif($model->status == 3){
                            return 'Не удовлетворено';
                        }
                        else{
                            return '<span class="not-set">(не задано)</span>';
                        }
                    }
                ],
                //'answers:ntext',
                //todo перенести в отдельную вкладку
                //'comment:ntext',
                [
                    'attribute' => 'category_id',
                    'label' => 'Категория обращения',
                    'value' => function($model){
                        if(isset($model->category_id)){
                            return Categories::findOne($model->category_id)->name;
                        }
                        else{
                            return $model->category_id;
                        }
                        
                    }
                ],
                [
                    'attribute' => 'city_id',
                    'label' => 'Город',
                    'value' => function($model){
                        if(isset($model->city_id)){
                            return Cities::findOne($model->city_id)->name;
                        }
                        else{
                            return $model->city_id;
                        }
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Ответственный',
                    'value' => function($model){
                        if(isset($model->user_id)){
                            return Users::findOne($model->user_id)->snm;
                        }
                        else{
                            return $model->user_id;
                        }
                    }
                ],
                [
                    'attribute' => 'creation_date',
                    'label' => 'Дата создания обращения',
                    'value' => function ($model) {
                        $dateTime = new DateTime($model->creation_date, new DateTimeZone('Europe/Moscow'));
                        return Yii::$app->formatter->asDatetime($dateTime, 'php:d.m.Y H:i:s');
                    },
                ],
                [
                    'attribute' => 'last_change',
                    'label' => 'Дата последнего изменения',
                    'value' => function ($model) {
                        $dateTime = new DateTime($model->last_change, new DateTimeZone('Europe/Moscow'));
                        return Yii::$app->formatter->asDatetime($dateTime, 'php:d.m.Y H:i:s');
                    },
                ]
            ],
        ]) 
        ?>
    </div>
    <div class="col-2 p-0">
        <div id="ticket-view-sidebar-header" class="border-bottom border-dark text-center">
            <span class="text-nowrap">Документы по категории</span>
        </div>
        <div id="ticket-view-sidebar-content">
            <?php
                if(!isset($model->category_id)){
                    echo '<span class="text-danger">Категория не задана</span>';
                }
                else{
                    echo 'Список файлов по категории:' . PHP_EOL;
                }
            ?>
        </div>
    </div>
</div>

<script>

    function setModalHeader(header){
        let modalHeader = document.getElementById('ModalLabel');
        modalHeader.innerHTML = header;
    }

    function showTextMore(content, from){
        let button;
        if(from === 'title'){
            button = document.getElementById('ticket-view-title');
        }
        else{
            button = document.getElementById('ticket-view-text');
        }
        let modalContent = document.getElementById('ModalContent');
        modalContent.innerHTML = content;
        button.innerHTML = `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#Modal">Показать</button>`;
    }
</script>