<?php

namespace app\controllers;

use app\models\users;
use app\models\UserSearch;

/**
 * UserController implements the CRUD actions for Users model.
 */
class UserController extends AppController{

    public function behaviors() : array{
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin']
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'block' => ['POST'],
                    'reset' => ['POST']
                ]
            ]
        ];
    }

    /**
     * Lists all users models.
     *
     * @return string
     */
    public function actionIndex() : string{
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Users model.
     * @param int $id ID
     * @return string
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found or forbidden
     */
    public function actionView(int $id) : string{
        if($id < 10 && \Yii::$app->user->identity->id >= 10){
            throw new \yii\web\ForbiddenHttpException('Доступ запрещен.');
        }
        else{
            return $this->render('view', [
                'model' => $this->findModel($id)
            ]);
        }
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() : string|\yii\web\Response{
        $model = new Users(['scenario' => 'signup']);
        if($model->load(\Yii::$app->request->post())){
                if(isset(\Yii::$app->request->post('Users')['password_repeat']) && \Yii::$app->request->post('Users')['password_repeat'] === $model->password){
                $model->auth_key = \Yii::$app->security->generateRandomString(64);
                if($model->validate()){
                    $model->password =  \Yii::$app->security->generatePasswordHash($model->password);
                    $authManager = \Yii::$app->authManager;
                    if($model->save()){
                        $authManager->assign($authManager->getRole('user'), $model->id);
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    else{
                        \Yii::$app->session->addFlash('error', 'Произошла ошибка при регистрации');
                    }
                }
            }
            else{
                \Yii::$app->session->addFlash('error', 'Пароли должны совпадать');
            }
        }
        $model->password = '';
        $model->password_repeat = '';
        $model->auth_key = '';
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found or forbidden
     */
    public function actionUpdate(int $id) : string|\yii\web\Response{
        if($id < 10 && \Yii::$app->user->identity->id >= 10){
            throw new \yii\web\ForbiddenHttpException('Доступ запрещен.');
        }
        else{
            $model = $this->findModel($id);
            if(\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())){
                if(isset($model->tg_user_id)){
                    $updates['user'][$id]['snm'] = $model->snm;
                    $updates['user'][$id]['event'] = 'update';
                    $updates['user'][$id]['tg_user_id'] = $model->tg_user_id;
                }
                if($model->save()){
                    \Yii::$app->session->addFlash('success', 'Сотрудник успешно изменен.');
                    if(isset($model->tg_user_id)){
                        $cache = \Yii::$app->cache->get('updates');
                        if($cache === false){
                            \Yii::$app->cache->set('updates', $updates, null);
                        }
                        else{
                            $cache['user'][$id] = $updates['user'][$id];
                            \Yii::$app->cache->set('updates', $cache, null);
                        }
                    }
                    return $this->redirect(['view', 'id' => $id]);
                }
            }
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found or forbidden
     */
    public function actionDelete(int $id) : \yii\web\Response{
        throw new \yii\web\ForbiddenHttpException('Доступ запрещен.');
    }

    /**
     * Block an existing Users model.
     * If block is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found or forbidden
     */
    public function actionBlock(int $id) : \yii\web\response{
        if($id < 10){
            if(\Yii::$app->user->identity->id === \Yii::$app->params['developerUserId'] && $id !== \Yii::$app->user->identity->id){
                if(\Yii::$app->authManager->checkAccess($id, 'user')){
                    \Yii::$app->authManager->revoke(\Yii::$app->authManager->getRole('user'), $id);
                    $tgUserId = $this->findModel($id)->tg_user_id;
                    if(isset($tgUserId)){
                        $updates['user'][$id]['event'] = 'ban';
                        $updates['user'][$id]['tg_user_id'] = $tgUserId;
                        $cache = \Yii::$app->cache->get('updates');
                        if($cache === false){
                            \Yii::$app->cache->set('updates', $updates, null);
                        }
                        else{
                            $cache['user'][$id] = $updates['user'][$id];
                            \Yii::$app->cache->set('updates', $cache, null);
                        }
                    }
                    \Yii::$app->session->addFlash('success', 'Сотрудник успешно заблокирован');
                }
                else{
                    \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('user'), $id);
                    $tgUserId = $this->findModel($id)->tg_user_id;
                    if(isset($tgUserId)){
                        $updates['user'][$id]['event'] = 'unban';
                        $updates['user'][$id]['tg_user_id'] = $tgUserId;
                        $cache = \Yii::$app->cache->get('updates');
                        if($cache === false){
                            \Yii::$app->cache->set('updates', $updates, null);
                        }
                        else{
                            $cache['user'][$id] = $updates['user'][$id];
                            \Yii::$app->cache->set('updates', $cache, null);
                        }
                    }
                    \Yii::$app->session->addFlash('success', 'Сотрудник успешно разблокирован');
                }

            }
            else{
                throw new \yii\web\ForbiddenHttpException('Доступ запрещен');
            }
        }
        else{
            if($id !== \Yii::$app->user->identity->id){
                if(!\Yii::$app->authManager->checkAccess($id, 'admin') || \Yii::$app->user->identity->id === \Yii::$app->params['developerUserId']){
                    if(\Yii::$app->authManager->checkAccess($id, 'user')){
                        \Yii::$app->authManager->revoke(\Yii::$app->authManager->getRole('user'), $id);
                        $tgUserId = $this->findModel($id)->tg_user_id;
                        if(isset($tgUserId)){
                            $updates['user'][$id]['event'] = 'ban';
                            $updates['user'][$id]['tg_user_id'] = $tgUserId;
                            $cache = \Yii::$app->cache->get('updates');
                            if($cache === false){
                                \Yii::$app->cache->set('updates', $updates, null);
                            }
                            else{
                                $cache['user'][$id] = $updates['user'][$id];
                                \Yii::$app->cache->set('updates', $cache, null);
                            }
                        }
                        \Yii::$app->session->addFlash('success', 'Сотрудник успешно заблокирован');
                    }
                    else{
                        \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('user'), $id);
                        $tgUserId = $this->findModel($id)->tg_user_id;
                        if(isset($tgUserId)){
                            $updates['user'][$id]['event'] = 'unban';
                            $updates['user'][$id]['tg_user_id'] = $tgUserId;
                            $cache = \Yii::$app->cache->get('updates');
                            if($cache === false){
                                \Yii::$app->cache->set('updates', $updates, null);
                            }
                            else{
                                $cache['user'][$id] = $updates['user'][$id];
                                \Yii::$app->cache->set('updates', $cache, null);
                            }
                        }
                        \Yii::$app->session->addFlash('success', 'Сотрудник успешно разблокирован');
                    }
                }
                else{
                    \Yii::$app->session->addFlash('error', 'Запрещено блокировать других администраторов');
                }
            }
            else{
                \Yii::$app->session->addFlash('error', 'Запрещено блокировать свою учетную запись');
            }
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Reset password an existing Users model.
     * If reset is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found or forbidden
     */
    public function actionReset(int $id) : \yii\web\response{
        if($id < 10){
            if(\Yii::$app->user->identity->id === \Yii::$app->params['developerUserId'] && $id !== \Yii::$app->user->identity->id){
                $model = $this->findModel($id);
                $password = \Yii::$app->security->generateRandomString(12);
                $model->updateAttributes(['password' => \Yii::$app->security->generatePasswordHash($password)]);
                \Yii::$app->session->addFlash('success', 'Пароль успешно сброшен | Новый пароль: ' . $password);
            }
            else{
                throw new \yii\web\ForbiddenHttpException('Доступ запрещен');
            }
        }
        else{
            if($id !== \Yii::$app->user->identity->id){
                $model = $this->findModel($id);
                $password = \Yii::$app->security->generateRandomString(8);
                $model->updateAttributes(['password' => \Yii::$app->security->generatePasswordHash($password)]);
                \Yii::$app->session->addFlash('success', 'Пароль успешно сброшен | Новый пароль: ' . $password);
            }
            else{
                \Yii::$app->session->addFlash('error', 'Запрещено сбрасывать пароль своей учетной записи');
            }
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id ID
     * @return \yii\web\response
     */
    public function actionTgVerify(int $id) : \yii\web\response{
        $model = $this->findModel($id);
        if(!isset($model->tg_user_id)){
            if(\Yii::$app->cache->get('tg' . $id) === false){
                $verifyCode = 'verify' . \Yii::$app->security->generateRandomString(6);
                \Yii::$app->cache->set('tg' . $id, $verifyCode, 3600);
            }
            else{
                $verifyCode = \Yii::$app->cache->get('tg' . $id);
            }
            \Yii::$app->session->addFlash('success', 'Код необходимо ввести в бота telegram: ' . $verifyCode . '*||*' . $id . '<br>Код будет действовать 1 час.');
            return $this->redirect(['view', 'id' => $id]);
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Users the loaded model
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Users{
        if(($model = Users::findOne(['id' => $id])) !== null){
            return $model;
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }
}