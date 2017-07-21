<?php

namespace backend\modules\suadmin;

use Yii;
use yii\base\Module;
use yii\base\Theme;

/**
 * suadmin module definition class
 */
class suadmin extends Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\suadmin\controllers';

    /**
     * @inheritdoc
     */
    public function init() {

        if (isset(Yii::$app->session['module_name'])) {
            if (Yii::$app->session['module_name'] != 'suadmin') {
                Yii::$app->user->logout();
                Yii::$app->session['module_name'] = "suadmin";
            }
        } else {
            Yii::$app->session['module_name'] = "suadmin";
        }

        $this->layout = "@app/modules/suadmin/views/layouts/main";

        $homeurl = Yii::$app->getHomeUrl() . 'suadmin/';
        Yii::$app->setHomeUrl($homeurl);

        parent::init();

        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\DlSuperAdmin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => 'super-admin', 'httpOnly' => true],
             //  'idParam' => 'editor_id', //this is important !
             'returnUrl'=>array('suadmin/default/index'),
             'loginUrl'=>array('suadmin/site/login'),


        ]);

        Yii::$app->view->theme = new Theme([
            'pathMap' => ['@app/views' => '@app/themes/suadmin/views'],
            'baseUrl' => '@web/themes/suadmin',
        ]);
    }

}
