<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'formatter' => [
            'dateFormat' => 'php:d-m-Y',
            'datetimeFormat' => 'php:d-m-Y H:i:s',
            'timeFormat' => 'php:h:i A',
        ],
        'myclass' => [
            'class' => 'common\components\Myclass',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                [
                    'pattern' => '/book/<aid:>',
                    'route' => '/affiliate/booking/index'
                ],
                [
                    'pattern' => '/course/<cid:>/<step:>',
                    'route' => '/affiliate/booking/course',
                    'defaults' => ['step' => 2],
                ],
                [
                    'pattern' => '/myaccount',
                    'route' => '/affiliate/user/index',
                ],
                [
                    'pattern' => '/login',
                    'route' => '/affiliate/default/login',
                ],
                [
                    'pattern' => '/forgot-password',
                    'route' => '/affiliate/default/request-password-reset',
                ],
                [
                    'pattern' => '/course',
                    'route' => '/affiliate/course/index',
                ],
                [
                    'pattern' => '/payment',
                    'route' => '/affiliate/payment/index',
                ],
                [
                    'pattern' => '/profile',
                    'route' => '/affiliate/user/update',
                ],
                [
                    'pattern' => '/myaccount-autologin/<uid:>/',
                    'route' => '/affiliate/user/autologin',
                ],
                [
                    'pattern' => '/instructoralert',
                    'route' => '/affiliate/cron/instructoralert',
                ],
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'thousandSeparator' => ',',
            'decimalSeparator' => '.',
            'currencyCode' => '$',
            'dateFormat' => 'php:d-m-Y',
            'timeFormat' => 'php:h:i a',
            'datetimeFormat' => 'php:d-m-Y h:i a'
        ],
    ],
];
