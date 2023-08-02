<?php

namespace app\controllers;

class AppController extends \yii\web\Controller{

    public function behaviors() : array{
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
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
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

    protected static function debug(mixed $data, $mode = false) : void{
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        if($mode){exit(0);}
    }
    
    public function beforeAction($action) : bool{
        \Yii::$app->session->set('csrf', md5(uniqid(rand(), true)));
        return parent::beforeAction($action);
    }
}