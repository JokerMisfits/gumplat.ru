<?php

namespace app\controllers;

use yii;

class AppController extends yii\web\Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['user']
                    ]
                ]
            ],
            'verbs' => [
                'class' => yii\filters\VerbFilter::class,
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

    protected static function debug($data, $mode = false){
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        if($mode){exit(0);}
    }
    
    public function beforeAction($action){
        Yii::$app->session->set('csrf', md5(uniqid(rand(), true)));
        return parent::beforeAction($action);
    }

}