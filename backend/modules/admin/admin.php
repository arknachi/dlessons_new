<?php

namespace backend\modules\admin;

use Yii;
use yii\base\Module;
use yii\base\Theme;

/**
 * admin module definition class
 */
class admin extends Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init() {

        if (isset(Yii::$app->session['module_name'])) {
            if (Yii::$app->session['module_name'] != 'admin') {
                Yii::$app->user->logout();
                Yii::$app->session['module_name'] = "admin";
            }
        } else {
            Yii::$app->session['module_name'] = "admin";
        }

        $this->layout = "@app/modules/admin/views/layouts/column1";

        $homeurl = Yii::$app->getHomeUrl() . 'admin/';
        Yii::$app->setHomeUrl($homeurl);        
        
        parent::init(); 
           
        $session = Yii::$app->session;
   
        if (isset($_POST['DlCity']['city_id'])) {
            $session->set('cityid', $_POST['DlCity']['city_id']);          
        } else if ($session->has('cityid')) {
            
        } else {
            $session->set('cityid', null);
        }


        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\DlAdmin',
            'enableAutoLogin' => true,
            // 'loginUrl' => ['yonetim/default/login'],
            'identityCookie' => ['name' => 'admin', 'httpOnly' => true],
            //  'idParam' => 'editor_id', //this is important !
            'returnUrl' => array('admin/default/index'),
            'loginUrl' => array('admin/site/login'),
        ]);

        Yii::$app->view->theme = new Theme([
            'pathMap' => ['@app/views' => '@app/themes/admin/views'],
            'baseUrl' => '@web/themes/admin',
        ]);
    }
    
}
