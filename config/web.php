<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Demo',
    'version' => '1.0.0',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-Ru',
    'bootstrap' => [
        'log',
        'queue'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
    ],
    'components' => [
        'request' => [
            'baseUrl'=> '',
            'cookieValidationKey' => 'lcxpqay5DsopWsl3cV6TXxYebvdxisi-'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true
        ],
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace']
                ]
            ]
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<action:(index|login|verify-tg|check-updates|login-by-access-token|logout)>' => 'site/<action>',
                'tickets' => 'ticket/index',
                'cities' => 'city/index',
                'categories' => 'category/index',
                'documents' => 'document/index',
                'users' => 'user/index',
                'download/<id:\d+>' => 'document/download-file',
                'downloadtg/<path:[\w-]+>' => 'document/download-file-from-tg'
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%auth_item}}',
            'assignmentTable' => '{{%auth_assignment}}',
            'ruleTable' => '{{%auth_rule}}',
            'itemChildTable' => '{{%auth_item_child}}',
            'defaultRoles' => ['guest']
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class
        ]
    ],
    'timeZone' => 'Europe/Moscow',
    'params' => $params
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '198.16.66.101'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'job' => [
                'class' => yii\queue\gii\Generator::class
            ]
        ],
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1' , '198.16.66.101'],
    ];
}

return $config;