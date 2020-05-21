<?php
declare(strict_types = 1);

use app\modules\system\models\Users;
use yii\gii\Module as ModuleGii;
use yii\log\FileTarget;
use yii\debug\Module as ModuleDebug;
use yii\swiftmailer\Mailer;
use yii\caching\FileCache;
use app\modules\customer\Customer;
use app\modules\administrator\Administrator;
use app\modules\system\System;

$params = require sprintf('%s/params.php', __DIR__);
$dbs    = require sprintf('%s/db.php', __DIR__);

$config = [
    'id' => 'basic',
    'bootstrap' => ['log'],
    'basePath' => dirname(__DIR__),
    'language' => 'ru',
    'defaultRoute' => 'site/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'system' => [
            'class' => System::class,
        ],
        'administrator' => [
            'class' => Administrator::class,
        ],
        'customer' => [
            'class' => Customer::class,
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'M3OZ1avXAMFedo-IhY0esh3DAr5w-aqi',
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'user' => [
            'identityClass' => Users::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $dbs['db'],
        'dbsu' => $dbs['dbsu'],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => ModuleDebug::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => ModuleGii::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
