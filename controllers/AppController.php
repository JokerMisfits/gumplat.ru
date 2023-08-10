<?php

namespace app\controllers;

class AppController extends \yii\web\Controller{

    /**
     * {@inheritdoc}
     * @return array
     */
    public function behaviors() : array{
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'verify-tg', 'check-updates', 'login-by-access-token'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'roles' => ['user']
                    ],
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'logout' => ['POST'],
                    'verify-tg' => ['POST'],
                    'check-updates' => ['POST'],
                    'login-by-access-token' => ['GET']
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     * @param mixed $data Data
     * @param bool $mode Debug mode
     * @return void
     */
    protected static function debug(mixed $data, $mode = false) : void{
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        if($mode){exit(0);}
    }

    /** 
     * @param array $data message
     * @param string $method
     * @return bool|string
     */
    protected static function curlSendData(array $data, string $method = '/sendMessage') : bool|string{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $_SERVER['BOT_TOKEN'] . $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'accept: application/json'));
        $result = curl_exec($ch);
        if($result === false){
            \Yii::error('Ошибка отправки сообщения в telegram: ' . curl_error($ch), 'curl');
            return false;
        }
        curl_close($ch);
        return $result;
    }

}