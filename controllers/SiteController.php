<?php

namespace app\controllers;

use app\models\Users;

class SiteController extends AppController{

    /**
     * {@inheritdoc}
     */
    public function actions() : array{
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex() : string|\yii\web\Response{
        return $this->redirect('/tickets');
    }

    /**
     * Login action.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin() : string|\yii\web\Response{
        if(!\Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $model = new Users(['scenario' => 'login']);
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                if($model->login()){
                    return $this->goBack();
                }
                else{
                    $model->password = '';
                    return $this->render('login', [
                        'model' => $model
                    ]);
                }
            }
            else{
                $model->password = '';
                return $this->render('login', [
                    'model' => $model
                ]);
            }
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * Logout action.
     *
     * @return \yii\web\Response
     */
    public function actionLogout() : \yii\web\Response{
        \Yii::$app->user->logout();
        return $this->goHome();
    }
}