<?php

use yii\helpers\ArrayHelper;

$params = require(__DIR__ . '/params.php');
$db = file_exists(__DIR__ . '/db-local.php') ?
    ArrayHelper::merge(
        require(__DIR__ . '/db.php'),
        require(__DIR__ . '/db-local.php')
    ) : require(__DIR__ . '/db.php');

$sendLocalMail = file_exists(__DIR__ . '/db-local.php') ? true : false;

$config = [
    'id' => 'basic',
    'name' => 'Elitka CRM',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'defaultRoute' => 'site/intro',
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            'enableStrictParsing' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/apartment',
                    'extraPatterns' => [
                        'GET detail' => 'detail',
                        'GET filter-count' => 'filter-count',
                        'GET companies' => 'companies',
                        'GET favs' => 'favs',
                        'POST fav' => 'fav',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/chat',
                    'extraPatterns' => [
                        'POST add' => 'add',
                        'POST viewed' => 'viewed',
                        'POST archive' => 'archive',
                        'GET load' => 'load',
                        'GET list' => 'list',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/page',
                    'extraPatterns' => [
                        'POST auth' => 'auth',
                        'POST edit' => 'edit',
                    ],
                ],
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'apartment/loadproposal/<id:\d+>/<usr:\d+>' => 'apartment/loadproposal',
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'a-tsLDPUqZz1Dfo9CLZwfKSZBbceF7wX',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => $sendLocalMail, //set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'elitkacrm@gmail.com',
                'password' => 'elitkacrm85qw',
                'port' => '587',
                'encryption' => 'tls',
            ]
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
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'modules' => [
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'user' => [
            'modelMap' => [
                'User' => 'app\models\User',
                'Profile' => 'app\models\Profile',
                'LoginForm' => 'app\models\LoginForm',
            ],
            'class' => 'dektrium\user\Module',
            'controllerMap' => [
                'admin' => 'app\controllers\user\AdminController',
                'security' => 'app\controllers\SecurityController',
                //'registration' => 'app\controllers\user\RegistrationController',
            ],
            'admins' => ['admin']
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
        'apt' => [
            'class' => 'app\modules\apt\Module',
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
