<?php

namespace app\controllers;

use app\models\Cities;
use app\models\Tickets;
use app\models\TicketSearch;

/**
 * TicketController implements the CRUD actions for Tickets model.
 */
class TicketController extends AppController{

    /**
    * @inheritDoc
    * @return array
    */
   public function behaviors() : array{
       return array_merge(
           parent::behaviors(),
           [
               'verbs' => [
                   'class' => \yii\filters\VerbFilter::class,
                   'actions' => [
                       'delete' => ['POST'],
                       'message-file' => ['POST']
                   ]
               ]
           ]
       );
   }

    /**
     * Lists all Tickets models.
     *
     * @return string
     */
    public function actionIndex() : string{
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(\yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cities' => Cities::find()->asArray()->all()
        ]);
    }

    /**
     * Displays a single Tickets model.
     * @param int $id ID
     * @return string
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id) : string{
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new Tickets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() : string|\yii\web\Response{
        $model = new Tickets();
        $model->user_id = \Yii::$app->params['systemUserId'];
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                if($model->save()){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    \Yii::$app->session->addFlash('error', 'Произошла ошибка при создании обращения');                  
                    return $this->render('create', [
                        'model' => $model
                    ]);
                }
            }
        }
        else{
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Tickets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id) : string|\yii\web\Response{
        $model = $this->findModel($id);
        if($model->load(\yii::$app->request->post())){
            if($model->validate()){
                if($model->status == 2 || $model->status == 3){
                    if($model->comment == '' || !isset($model->comment) || strlen($model->comment) < 6){
                        \Yii::$app->session->addFlash('error', 'Необходимо заполнить результаты рассмотрения, перед закрытием обращения.<br>Минимум 6 символов.');     
                    }
                    else{
                        $model->limit = 0;
                        $model->is_new = 0;
                        if($model->save()){
                            \Yii::$app->session->addFlash('success', 'Обращение успешно закрыто.');
                            $updates['ticket'][$id]['status'] = $model->status;
                            $updates['ticket'][$id]['event'] = 'delete';
                            $updates['ticket'][$id]['tg_user_id'] = $model->tg_user_id;
                            if(isset($model->tg_user_id)){
                                $cache = \Yii::$app->cache->get('updates');
                                if($cache === false){
                                    \Yii::$app->cache->set('updates', $updates, null);
                                }
                                else{
                                    $cache['ticket'][$id] = $updates['ticket'][$id];
                                    \Yii::$app->cache->set('updates', $cache, null);
                                }
                            }
                            return $this->redirect(['view', 'id' => $id]);
                        }
                        else{
                            \Yii::$app->session->addFlash('error', 'Произошла ошибка при обновлении обращения.');
                        }
                    }
                }
                else{
                    if($model->save()){
                        \Yii::$app->session->addFlash('success', 'Обращение успешно изменено.');
                        return $this->redirect(['view', 'id' => $id]);
                    }
                    else{
                        \Yii::$app->session->addFlash('error', 'Произошла ошибка при обновлении обращения.'); 
                    }
                }
            }
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Tickets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete(int $id) : \yii\web\Response{
        if(\Yii::$app->user->can('admin')){
            $model = $this->findModel($id);
            if($model->tg_user_id === null){
                if($model->delete() !== false){
                    \Yii::$app->session->addFlash('success', 'Обращение №' . $id . ' успешно удалено.');    
                }
                else{
                    \Yii::$app->session->addFlash('error', 'Произошла ошибка при удалении обращения №' . $id . '.');    
                }
            }
            else{
                \Yii::$app->session->addFlash('warning', 'Запрещено удалять обращения созданные при помощи бота в telegram, это действие навредит целостности данных.'); 
            }
            return $this->redirect(['index']);
        }
        else{
            throw new \yii\web\ForbiddenHttpException('Доступ запрещен.');
        }
    }


   /** 
     * @param int $id ID
     * @param int $tg_user_id tg_user_id
     * @return \yii\web\Response
     */
    public function actionMessageText(int $id, string $message) : \yii\web\Response{
        $model = $this->findModel($id);
        $transaction = $model->getDb()->beginTransaction();
        try{
            $message = [
                'type' => 'text',
                'author' => \Yii::$app->user->identity->tg_user_id,
                'message' => $message
            ];
            $model->messages = array_merge($model->messages, [$message]);
            $model->limit = \Yii::$app->params['limitAfterResponse'];
            $model->is_new = 0;
            $updates['ticket'][$id]['messages'] = $model->messages;
            $updates['ticket'][$id]['event'] = 'update';
            $updates['ticket'][$id]['tg_user_id'] = $model->tg_user_id;
            $model->updateAttributes(['messages', 'limit', 'is_new']);
            $transaction->commit();
            $cache = \Yii::$app->cache->get('updates');
            if($cache === false){
                \Yii::$app->cache->set('updates', $updates, null);
            }
            else{
                $cache['ticket'][$id] = $updates['ticket'][$id];
                \Yii::$app->cache->set('updates', $cache, null);
            }
            \Yii::$app->session->addFlash('success', 'Сообщение успешно отправлено.');
        }
        catch(\Exception|\Throwable $e){
            $transaction->rollBack();
            \Yii::error('Ошибка при обновлении сообщения в Ticket::' . $id . ' | ' . $e->getMessage(), 'tickets');
            \Yii::$app->session->addFlash('error', 'Произошла ошибка при отправке сообщения.');
        }
        \Yii::$app->request->queryParams = [];
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id ID
     * @param int $tg_user_id tg_user_id
     * @return \yii\web\Response
     */
    public function actionMessageFile(int $id) : \yii\web\Response{
        if(\Yii::$app->request->isPost){
            $name = explode('.', $_FILES['Documents']['name']['file']);
            $name = \Yii::$app->security->generateRandomString(6) . '.' . end($name);
            $savePath = realpath(\Yii::getAlias('@web')) . '/documents/' . $name;
            move_uploaded_file($_FILES['Documents']['tmp_name']['file'], $savePath);
            $data = [
                'document' => \Yii::$app->params['host'] . '/web/documents/' . $name,
                'chat_id' => \Yii::$app->params['fileChatId']
            ];
            $response = json_decode(AppController::curlSendData($data, '/sendDocument'), true);
            if(array_key_exists('ok', $response) && $response['ok'] === true){
                if((array_key_exists('thumbnail', $response['result']['document']) || array_key_exists('thumb', $response['result']['document'])) && $response['result']['document']['mime_type'] !== 'application/pdf'){
                    $type = 'photo';
                }
                else{
                    $type = 'document';
                }
                $data = [
                    'file_id' => $response['result']['document']['file_id']
                ];
                $response = json_decode(AppController::curlSendData($data, '/getFile'), true);
                if(array_key_exists('ok', $response) && $response['ok'] === true){
                    $model = $this->findModel($id);
                    $transaction = $model->getDb()->beginTransaction();
                    try{
                        $message = [
                            'type' => $type,
                            'author' => \Yii::$app->user->identity->tg_user_id,
                            'message' => 'https://api.telegram.org/file/bot' . $_SERVER['BOT_FILE_TOKEN'] . '/' . $response['result']['file_path']
                        ];
                        $model->messages = array_merge($model->messages, [$message]);
                        $model->limit = \Yii::$app->params['limitAfterResponse'];
                        $model->is_new = 0;
                        $updates['ticket'][$id]['messages'] = $model->messages;
                        $updates['ticket'][$id]['event'] = 'message';
                        $updates['ticket'][$id]['tg_user_id'] = $model->tg_user_id;
                        $model->updateAttributes(['messages', 'limit']);
                        $transaction->commit();
                        $cache = \Yii::$app->cache->get('updates');
                        if($cache === false){
                            \Yii::$app->cache->set('updates', $updates, null);
                        }
                        else{
                            $cache['ticket'][$id] = $updates['ticket'][$id];
                            \Yii::$app->cache->set('updates', $cache, null);
                        }
                        \Yii::$app->session->addFlash('success', 'Сообщение успешно отправлено.'); 
                    }
                    catch(\Exception|\Throwable $e){
                        $transaction->rollBack();
                        \Yii::error('Ошибка при обновлении сообщения(файл) в Ticket::' . $id . ' | ' . $e->getMessage(), 'tickets');
                        \Yii::$app->session->addFlash('error', 'Произошла ошибка при отправке сообщения.');
                    }
                }
                else{
                    \Yii::error('Ошибка при подгрузке файла в директорию бота в telegram ' . json_encode($response), 'tickets');
                    \Yii::$app->session->addFlash('error', 'Произошла ошибка при обработке сообщения на сервере telegram.');
                }
            }
            else{
                \Yii::error('Ошибка при отправке сообщения(файл) в telegram ' . json_encode($response), 'tickets');
                \Yii::$app->session->addFlash('error', 'Произошла ошибка при отправке сообщения.');
            }
            unlink($savePath);
        }
        \Yii::$app->request->queryParams = [];
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Tickets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tickets the loaded model
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Tickets{
        if(($model = Tickets::findOne(['id' => $id])) !== null){
            return $model;
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }
}