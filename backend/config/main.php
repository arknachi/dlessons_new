<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'suadmin' => [
            'basePath' => '@app/modules/suadmin',
            'class' => 'backend\modules\suadmin\suadmin',
           // 'defaultRoute' => 'site/home',
        ],
        'admin' => [
            'basePath' => '@app/modules/admin',
            'class' => 'backend\modules\admin\admin',

        ],
        'instructor' => [
            'basePath' => '@app/modules/instructor',
            'class' => 'backend\modules\instructor\instructor',

        ],
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
    ],
    'homeUrl' => '/webpanel',
    'components' => [

        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/webpanel',
        ],
        'user' => [
          //   'class' => 'yii\web\User', // basic class
            'identityClass' => 'common\models\DlSuperAdmin', // your admin model
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'admin/site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
    'defaultRoute' => 'admin/site/index',
];
