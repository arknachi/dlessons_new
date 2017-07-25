<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/bootstrap-datepicker.min.css',
        'css/style.css',
        'css/reponsive.css',
        'css/custom.css',
    ];
    public $js = [
        '//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js',
        '//oss.maxcdn.com/respond/1.4.2/respond.min.js',
        'js/bootstrap.min.js',
        'js/bootstrap-datepicker.min.js',
        'js/main.js',
        'js/jquery.payform.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
