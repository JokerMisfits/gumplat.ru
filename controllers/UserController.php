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
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id) : string{
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response|\yii\widgets\ActiveForm
     */
    public function actionCreate() : string|\yii\web\Response|\yii\widgets\ActiveForm{
        $model = new Users(['scenario' => 'signup']);
        if(\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
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
                        $model->password = '';
                        $model->password_repeat = '';
                        return $this->render('create', [
                            'model' => $model
                        ]);
                    }
                }
                else{
                    $model->password = '';
                    $model->password_repeat = '';
                    return $this->render('create', [
                        'model' => $model
                    ]);
                }
            }
            else{
                \Yii::$app->session->addFlash('error', 'Пароли должны совпадать');
                $model->password = '';
                $model->password_repeat = '';
                $model->auth_key = '';
                return $this->render('create', [
                    'model' => $model
                ]);
            }
        }
        else{
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id) : string|\yii\web\Response{
        $model = $this->findModel($id);
        if(\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post()) && $model->save()){
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException|\yii\web\ForbiddenHttpException if the model cannot be found
     */
    public function actionDelete(int $id) : \yii\web\Response{
        throw new \yii\web\ForbiddenHttpException('Доступ только у разработчиков');
        // $this->findModel($id)->delete();
        // return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Users the loaded model
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id) : Users{
        if(($model = users::findOne(['id' => $id])) !== null){
            return $model;
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }
}