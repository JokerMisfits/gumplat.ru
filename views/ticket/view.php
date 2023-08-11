<?php

use app\models\Users;
use app\models\Cities;
use app\models\Documents;
use app\models\Categories;

/** @var yii\web\View $this */
/** @var app\models\Tickets $model */

$this->title = 'Обращение №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
yii\web\YiiAsset::register($this);
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', [
    'crossorigin' => 'anonymous',
    'position' => $this::POS_HEAD,
]);
?>

<style>
    @media(max-width: 767px){
        #tickets-view-content{
            border: none!important;
        }
        #ticket-view-sidebar{
            border-top: 1px solid #212529;
        }
        #ticket-view-sidebar-header{
            border: none!important;
        }
    }
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
                <!-- <div class="col-12 text-start">
                    <a href="https://t.me/Xo_Diamond_XO" class="link-danger link-offset-2 link-underline-opacity-30 link-underline-opacity-100-hover" title="Напишите, чтобы заказать расширение функционала проекта.">Связь с разработчиком</a> <i class="fas fa-laptop-code"></i>
                </div> -->
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div class="tickets-view row p-0 m-0 table-responsive bg-light border-top border-bottom border-dark">
    <div id="tickets-view-content" class="col-12 col-md-9 border-end border-dark" style="padding: 0 0 0 2px;">
        <h1 class="text-start"><?= 'Обращение №' . yii\helpers\Html::encode($model->id); ?></h1>
        <p>
            <?= yii\helpers\Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mt-1']); ?>
            <button id="ticket-result-button" class="btn btn-dark mt-1" onclick="showResult();">Показать результаты рассмотрения</button>
            <?php
                if(Yii::$app->user->can('admin')){
                echo yii\helpers\Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger mt-1',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить данное обращение?',
                        'method' => 'post',
                    ]
                ]);
                }
            ?>
        </p>

        <?php echo '<div id="ticket-result" style="display: none;">' . $this->render('result', ['model' => $model]) . '</div>'; ?>

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
                        if(strlen($model->title) > 32){
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
                        if(strlen($model->text) > 32){
                            $js = <<<JS
                                showTextMore("$model->text", 'text');
                            JS;
                            return $this->registerJs($js);
                        }
                        return $model->text;
                    }
                ],
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
                    'value' => function($model){
                        $dateTime = new DateTime($model->creation_date, null);
                        return Yii::$app->formatter->asDatetime($dateTime, 'php:d.m.Y H:i:s');
                    }
                ],
                [
                    'attribute' => 'last_change',
                    'label' => 'Дата последнего изменения',
                    'value' => function($model){
                        $dateTime = new DateTime($model->last_change, null);
                        return Yii::$app->formatter->asDatetime($dateTime, 'php:d.m.Y H:i:s');
                    }
                ]
            ],
        ]) 
        ?>
    </div>
    <div id="ticket-view-sidebar" class="col-12 col-md-3 p-0">
        <div id="ticket-view-sidebar-header" class="border-bottom border-dark text-center px-2">
            <span class="text-nowrap"><?= $model->category_id !== null ? 'Документы по категории: ' . Categories::findOne($model->category_id)->name : 'Документы по категории:'; ?></span>
        </div>
        <div id="ticket-view-sidebar-content" class="px-2 pb-2">
            <?php
                if(isset($model->category_id)){
                    $documents = Categories::findOne($model->category_id)->documents;
                    if(count($documents) > 0){
                        $count = count($documents);
                        for($i = 0; $i < $count; $i++){
                            echo '<div class="col-12 mt-1 text-center"><span class="text-nowrap">' . yii\helpers\Html::a($documents[$i]->base_name . '.' . $documents[$i]->extension, ['download/' . $documents[$i]->id], ['class' => 'link-primary link-offset-2 link-underline-opacity-50 link-underline-opacity-100-hover', 'title' => 'Скачать', 'target' => '_self']) . '</span></div>';
                        }
                    }
                    else{
                        echo '<span class="not-set">Файлы по заданной категории отсутствуют</span>';
                    }      
                }
                else{
                    echo '<span class="not-set">Категория не задана</span>';
                }
            ?>
        </div>
    </div>
</div>

<div class="col-12 container-fluid mb-4 bg-dark p-2 text-light">
    <?php 
        echo '<span>История сообщений:</span><hr class="text-danger my-2">';
        if(isset($model->tg_user_id)){
            if(array_key_exists(1, $model->messages)){
                $count = count($model->messages);
                for($i = 1; $i < $count; $i++){
                    if($model->messages[$i]['author'] === 'member'){
                        if($model->messages[$i]['type'] === 'text'){
                            echo '<div class="alert alert-primary">' . $i . ' Сообщение от клиента: ' . $model->messages[$i]['message'] . '</div>';
                        }
                        else{
                            $path = explode('/', $model->messages[$i]['message']);
                            if(array_key_exists(5, $path) && array_key_exists(6, $path)){
                                $file = explode('.', $path[6]);
                                echo '<div class="alert alert-primary">' . $i . ' Файл от клиента: ' . yii\helpers\Html::a('Скачать', ['downloadtg/' . $path[5] . '-' . $file[0] . '-' . $file[1]], ['class' => 'btn btn-danger btn-sm my-1', 'title' => 'Скачать', 'target' => '_self']) . '</div>';
                            }
                        }
                    }
                    else{
                        $snm = Users::findOne(['tg_user_id' => $model->messages[$i]['author']]);
                        if($snm !== null){
                            $snm = $snm->snm . '(юрист)';
                        }
                        else{
                            $snm = 'юриста';
                        }
                        if($model->messages[$i]['type'] === 'text'){
                            echo '<div class="alert alert-secondary">' . $i . ' Ответ от ' . $snm . ' : ' . $model->messages[$i]['message'] . '</div>';
                        }
                        else{
                            $path = explode('/', $model->messages[$i]['message']);
                            if(array_key_exists(5, $path) && array_key_exists(6, $path)){
                                $file = explode('.', $path[6]);
                                echo '<div class="alert alert-secondary">' . $i . ' Файл от ' . $snm . ' : ' . yii\helpers\Html::a('Скачать', ['downloadtg/' . $path[5] . '-' . $file[0] . '-' . $file[1]], ['class' => 'btn btn-danger btn-sm my-1', 'title' => 'Скачать', 'target' => '_self']) . '</div>';
                            }
                        }
                    }
                }
            }
            else{
                echo '<span class="not-set">Ничего не найдено.</span>';
            }
            if(isset(Yii::$app->user->identity->tg_user_id)){
                if(($model->status === 0 || $model->status === 1)){
                    echo '<hr class="text-danger my-2">';
                    echo '<button id="ticket-send-message-button" onclick="showSendMessage();" class="btn btn-warning btn-sm my-2 mx-1">Отправить текстовое сообщение</button> <button id="ticket-send-file-button" onclick="showSendFile();" class="btn btn-warning btn-sm my-2 mx-1">Отправить документ</button>';
                    echo '<hr class="text-danger my-2">';
                    echo '<div id="ticket-send-message-form" style="display: none;>';
                    echo '<hr class="text-danger my-2">';
                    echo '<div class="form-floating text-dark col-12 col-md-8 col-lg-6 offset-md-2 offset-lg-3">';
                    echo '<textarea id="messageTextarea" class="form-control" placeholder="" id="floatingTextarea"></textarea>';
                    echo '<label id="messageTextLabel" for="floatingTextarea">Форма отправки сообщений в telegram</label>';
                    echo '</div>';
                    echo '<div class="d-flex justify-content-center my-2 mx-1">' . yii\helpers\Html::a('Отправить', ['message-text', 'id' => $model->id, 'tg_user_id' => $model->tg_user_id], [
                        'class' => 'btn btn-success col-12 col-md-8 col-lg-6',
                        'id' => 'sendMessageButton'
                    ]) . '</div>';
                    echo '</div>';
    
                    echo '<div id="ticket-send-file-form" style="display: none;>';
                    echo '<hr class="text-danger my-2">';
                    $form = yii\widgets\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'col-12 col-md-8 col-lg-6 offset-md-2 offset-lg-3'], 'action' => ['message-file', 'id' => $model->id, 'tg_user_id' => $model->tg_user_id]]);
                    echo '<strong id="messageTextLabel" for="floatingTextarea">Форма отправки документов в telegram</strong>';
                    echo $form->field(new Documents(), 'file', ['labelOptions' => ['class' => 'form-required']])->fileInput(['class' => 'form-control', 'type' => 'file']);
                    echo '<div class="form-group">';
                    echo yii\helpers\Html::submitButton('Отправить', ['class' => 'btn btn-success col-12']);
                    echo '</div>';
                    $form::end();
                    echo '</div>';
                }
                else{
                    echo '<hr class="text-danger my-2">';
                    echo '<span class="not-set">Обращение закрыто, возможность отправки сообщений отключена.</span>';
                }
            }
            else{
                echo '<hr class="text-danger my-2">';
                echo '<span class="not-set">Для отправки сообщений необходимо привязать ваш telegram к личному кабинету</span>';
            }
        }
        else{
            echo '<span class="not-set">ID пользователя в telegram не задан</span>';
        }
    ?>
    
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
        button.innerHTML = `<button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#Modal">Показать</button>`;
    }
</script>

<script>
    function showResult(){
        let form = document.getElementById('ticket-result');
        let button = document.getElementById('ticket-result-button');
        if(form.style.display === 'none'){
            form.style.display = 'block';
            button.innerText = 'Скрыть результаты рассмотрения';
        }
        else{
            form.style.display = 'none';
            button.innerText = 'Показать результаты рассмотрения';
        }
    }
</script>

<script>
    function showSendMessage(){
        let form = document.getElementById('ticket-send-message-form');
        let button = document.getElementById('ticket-send-message-button');
        let buttonFile = document.getElementById('ticket-send-file-button');
        if(form.style.display === 'none'){
            form.style.display = 'block';
            buttonFile.style.display = 'none';
            button.innerText = 'Скрыть форму отправки сообщения';
        }
        else{
            form.style.display = 'none';
            buttonFile.style.display = 'inline-block';
            button.innerText = 'Отправить текстовое сообщение';
        }
    }
</script>

<script>
    function showSendFile(){
        let form = document.getElementById('ticket-send-file-form');
        let button = document.getElementById('ticket-send-file-button');
        let buttonMessage = document.getElementById('ticket-send-message-button');
        if(form.style.display === 'none'){
            form.style.display = 'block';
            buttonMessage.style.display = 'none';
            button.innerText = 'Скрыть форму отправки документа';
        }
        else{
            form.style.display = 'none';
            buttonMessage.style.display = 'inline-block';
            button.innerText = 'Отправить документ';
        }
    }
</script>

<script>
    var sendMessageButton = document.getElementById("sendMessageButton");
    var messageTextarea = document.getElementById("messageTextarea");
    sendMessageButton.addEventListener("click", function(event){
        event.preventDefault();
        var messageValue = encodeURIComponent(messageTextarea.value);
        if(messageValue.length < 6 || messageValue.length > 2000){
            document.getElementById("messageTextLabel").innerHTML = '<span class="text-danger">Сообщение должно быть больше 6 символов и меньше 2000 символов!</span>';
        }
        else{
            let currentHref = sendMessageButton.getAttribute("href");
            window.location.href = currentHref + "&message=" + messageValue;
        }
    });
</script>