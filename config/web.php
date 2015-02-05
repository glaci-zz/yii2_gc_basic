<?php

//init vars
$server_host = $_SERVER['SERVER_NAME'];
$server_ip = gethostbyname($server_host);

var_dump($server_host);
var_dump($server_ip);

//DEV environment
if($server_ip == '54.251.167.231' || $server_ip == '127.0.0.1'){
    $debug_level = 'DEV';
    $db = require(__DIR__ . '/db_dev.php');
    $params = require(__DIR__ . '/params_dev.php');

    $asset_url = '';
//STG
//}elseif(){
//PRD
}else{
    $debug_level = 'PRD';
    $db = require(__DIR__ . '/db.php');
    $params = require(__DIR__ . '/params.php');
    $asset_url = 'gs://';

}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '27562952',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';
    $config['bootstrap'][] = 'gii';
#    $config['modules']['gii'] = 'yii\gii\Module';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*'],
    ];
}

return $config;
