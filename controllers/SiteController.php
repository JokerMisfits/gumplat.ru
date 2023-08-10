<?php

namespace app\controllers;

use app\models\Users;

class SiteController extends AppController{

    /**
     * {@inheritdoc}
     * @return array
     */
    public function actions() : array{
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    /**
     * Redirect to tickets page.
     *
     * @return \yii\web\Response
     */
    public function actionIndex() : \yii\web\Response{
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

    /**
     * Verify action.
     * @param int $id tg_user_id
     * @param string $code Verify code
     * @param string $hash Hash
     * @return \yii\web\Response
     * @throws \yii\web\ForbiddenHttpException if the model cannot be found
     */
    public function actionVerifyTg(int $id, string $code, string $hash) : \yii\web\Response{
        if(\Yii::$app->request->isPost && isset($code) && isset($hash)){
            if(md5($_SERVER['API_KEY_0'] . $code . $_SERVER['API_KEY_1']) === $hash){
                $code = explode('*||*' , $code);
                $verify = \Yii::$app->cache->get('tg' . $code[1]);
                if($verify !== false && $verify == $code[0]){
                    $model = Users::findOne(['id' => $code[1]]);
                    if(!isset($model->tg_user_id)){
                        if($model->updateAttributes(['tg_user_id' => $id, 'access_token' => \Yii::$app->security->generateRandomString(64)]) > 0){
                            exit('Ваш аккаунт успешно подтвержден. Данную страницу можно закрывать.');
                        }
                    }
                }
            }
        }
        throw new \yii\web\ForbiddenHttpException('Доступ запрещен.');
    }

    /**
     * @param int $id ID
     * @param int $tg_user_id Telegram user id
     * @param string $token Access token
     * @param string $hash Hash
     * @return \yii\web\response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionLoginByAccessToken(int $id, int $tg_user_id, string $token, string $hash){
        if(md5($_SERVER['API_KEY_0'] . $id . $tg_user_id . $token . $_SERVER['API_KEY_1']) === $hash){
            $user = Users::findIdentityByAccessToken($token);
            if($user !== null && $user->tg_user_id === $tg_user_id){
                \Yii::$app->user->login($user);
                return $this->redirect(['/tickets']);
            }
        }
        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }
}