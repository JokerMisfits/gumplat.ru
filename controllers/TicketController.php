<?php

namespace app\controllers;

use app\models\Users;
use app\models\Cities;
use app\models\Tickets;
use app\models\Categories;
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
            'dataProvider' => $dataProvider
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
                        'model' => $model,
                        'cities' => new Cities(),
                        'users' => new Users(),
                        'categories' => new Categories()
                    ]);
                }
            }
            else{
                return $this->render('create', [
                    'model' => $model,
                    'cities' => new Cities(),
                    'users' => new Users(),
                    'categories' => new Categories()
                ]);
            }
        }
        else{
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
            'cities' => new Cities(),
            'users' => new Users(),
            'categories' => new Categories()
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
                    if($model->comment == '' || !isset($model->comment) || strlen($model->comment) < 5){
                        \Yii::$app->session->addFlash('error', 'Необходимо заполнить результаты рассмотрения, перед закрытием обращения.');     
                    }
                    else{
                        $updates[$id] = $model->getDirtyAttributes();
                        if($model->save()){
                            $cache = \Yii::$app->cache->get('updates');
                            if($cache === false){
                                \Yii::$app->cache->set('updates' . $updates, 86400);
                            }
                            else{
                                $cache[$id] = $updates[$id];
                                \Yii::$app->cache->set('updates' . $cache, 86400);
                            }
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    }
                }
                else{
                    if($model->save()){
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                    'cities' => new Cities(),
                    'users' => new Users(),
                    'categories' => new Categories()
                ]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'cities' => new Cities(),
            'users' => new Users(),
            'categories' => new Categories()
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
                \Yii::$app->session->addFlash('warning', 'Запрещено удалять обращения созданные при помощи бота в telegram, это действие может навредить целостности данных.'); 
            }
            return $this->redirect(['index']);
        }
        else{
            throw new \yii\web\ForbiddenHttpException('Доступ только у администраторов');
        }
    }


   /** 
     * @param int $id ID
     * @param int $tg_user_id tg_user_id
     * @return \yii\web\Response
     */
    public function actionMessageText(int $id, int $tg_user_id, string $message) : \yii\web\Response{
        $model = $this->findModel($id);
        $transaction = $model->getDb()->beginTransaction();
        try{
            $messages = $model->messages;
            $message = [
                'type' => 'text',
                'author' => \Yii::$app->user->identity->tg_user_id,
                'message' => $message
            ];
            $messages[] = $message;
            $model->messages = $messages;
            $model->limit = 3;
            $model->is_new = 0;
            $updates[$id] = $model->getDirtyAttributes();
            $model->updateAttributes(['messages', 'limit', 'is_new']);
            $data = [
                'chat_id' => $tg_user_id,
                'text' => 'По вашему обращению пришел ответ от юриста',
                'reply_markup' => [
                    'inline_keyboard' => [
                        [  
                            [
                                'text' => 'Нажмите, чтобы прочесть сообщение',
                                'callback_data' => 'TICKETBLABLABLA',
                            ],
                        ]
                    ],
                    'resize_keyboard' => true
                ]
            ];
            if(AppController::curlSendData($data) !== false){
                $transaction->commit();
                $cache = \Yii::$app->cache->get('updates');
                if($cache === false){
                    \Yii::$app->cache->set('updates' . $updates, 86400);
                }
                else{
                    $cache[$id] = $updates[$id];
                    \Yii::$app->cache->set('updates' . $cache, 86400);
                }
                \Yii::$app->session->addFlash('success', 'Сообщение успешно отправлено.');
            }
            else{
                $transaction->rollBack();
                \Yii::$app->session->addFlash('error', 'Произошла ошибка при отправке сообщения.');
            }
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
    public function actionMessageFile(int $id, int $tg_user_id) : \yii\web\Response{
        if(\Yii::$app->request->isPost){
            $savePath = realpath(\Yii::getAlias('@web')) . '/documents/' . $_FILES['Documents']['name']['file'];
            move_uploaded_file($_FILES['Documents']['tmp_name']['file'], $savePath);
            $data = [
                'document' => \Yii::$app->params['host'] . '/web/documents/' . $_FILES['Documents']['name']['file'],
                'chat_id' => $tg_user_id
            ];
            $response = json_decode(AppController::curlSendData($data, '/sendDocument'), true);
            if(array_key_exists('ok', $response) && $response['ok'] === true){
                $data = [
                    'file_id' => $response['result']['document']['file_id']
                ];
                $response = json_decode(AppController::curlSendData($data, '/getFile'), true);
                if(array_key_exists('ok', $response) && $response['ok'] === true){
                    $model = $this->findModel($id);
                    $transaction = $model->getDb()->beginTransaction();
                    try{
                        $messages = $model->messages;
                        $message = [
                            'type' => 'document',
                            'author' => \Yii::$app->user->identity->tg_user_id,
                            'message' => 'https://api.telegram.org/file/bot' . $_SERVER['BOT_TOKEN'] . '/' . $response['result']['file_path']
                        ];
                        $messages[] = $message;
                        $model->messages = $messages;
                        $model->limit = 3;
                        $model->is_new = 0;
                        $updates[$id] = $model->getDirtyAttributes();
                        $model->updateAttributes(['messages', 'limit']);
                        $data = [
                            'chat_id' => $tg_user_id,
                            'text' => 'По вашему обращению пришел ответ от юриста',
                            'reply_markup' => [
                                'inline_keyboard' => [
                                    [  
                                        [
                                            'text' => 'Нажмите, чтобы прочесть сообщение',
                                            'callback_data' => 'TICKETBLABLABLA',
                                        ],
                                    ]
                                ],
                                'resize_keyboard' => true
                            ]
                        ];
                        if(AppController::curlSendData($data) !== false){
                            $transaction->commit();
                            $cache = \Yii::$app->cache->get('updates');
                            if($cache === false){
                                \Yii::$app->cache->set('updates' . $updates, 86400);
                            }
                            else{
                                $cache[$id] = $updates[$id];
                                \Yii::$app->cache->set('updates' . $cache, 86400);
                            }
                            \Yii::$app->session->addFlash('success', 'Сообщение успешно отправлено.');
                        }
                        else{
                            $transaction->rollBack();
                            \Yii::$app->session->addFlash('error', 'Произошла ошибка при отправке сообщения.');
                        }
                    }
                    catch(\Exception|\Throwable $e){
                        $transaction->rollBack();
                        \Yii::error('Ошибка при обновлении сообщения(файл) в Ticket::' . $id . ' | ' . $e->getMessage(), 'tickets');
                        \Yii::$app->session->addFlash('error', 'Произошла ошибка при отправке сообщения.');
                    }
                }
                else{
                    \Yii::$app->session->addFlash('error', json_encode($response) . 'Произошла ошибка при обработке сообщения на сервере telegram.');
                }
            }
            else{
                \Yii::$app->session->addFlash('error', 'Произошла ошибка при отправке сообщения.');
            }
            unlink($savePath);
        }
        \Yii::$app->request->queryParams = [];
        return $this->redirect(['view', 'id' => 34]);
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