<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAssetSuadmin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/themes/suadmin';
    public $css = [       
        'vendors/bootstrap/dist/css/bootstrap.min.css',
        'vendors/font-awesome/css/font-awesome.min.css',
        'build/css/custom-full.css',
        'css/datepicker/datepicker3.css',
        'css/custom.css'
    ];
    public $js = [
        'js/bootstrap.min.js',
        'build/js/custom.js',
        'js/datepicker/bootstrap-datepicker.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
