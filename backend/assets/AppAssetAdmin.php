<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAssetAdmin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/admin';
    public $css = [
       // 'css/site.css',
//        //'lib/bs3/css/bootstrap.css',
        'css/font-awesome/css/font-awesome.css',
    //    'css/bootstrap-theme.css',
        'css/AdminLTE.css',
        'css/ionicons.css',
        'css/jquery-ui.min.css',
        'css/jquery-ui-timepicker-addon.css',
        'css/bootstrap-select.css',
        'css/custom.css'

    ];
    public $js = [
       // 'js/jquery2.1.3.js',
        'js/bootstrap3.3.1.js',        
        'js/jquery-ui.min.js',
        'js/jquery-ui-timepicker-addon.js',
       // 'js/dropdown.js',
        'js/iCheck/icheck.js',
        'js/app.js',
        'js/bootstrap-select.js',        
        'js/custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
