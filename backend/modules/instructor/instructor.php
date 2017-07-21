<?php

namespace backend\modules\instructor;

use Yii;
use yii\base\Module;
use yii\base\Theme;

/**
 * instructor module definition class
 */
class instructor extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\instructor\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
         if (isset(Yii::$app->session['module_name'])) {
            if (Yii::$app->session['module_name'] != 'instructor') {
                Yii::$app->user->logout();
                Yii::$app->session['module_name'] = "instructor";
            }
        } else {
            Yii::$app->session['module_name'] = "instructor";
        }

        $this->layout = "@app/modules/instructor/views/layouts/column1";

        $homeurl = Yii::$app->getHomeUrl() . 'instructor/';
        Yii::$app->setHomeUrl($homeurl);

        parent::init();

        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\DlInstructors',
            'enableAutoLogin' => true,
            // 'loginUrl' => ['yonetim/default/login'],
            'identityCookie' => ['name' => 'instructor', 'httpOnly' => true],
                //  'idParam' => 'editor_id', //this is important !
            'returnUrl'=>array('instructor/default/index'),
            'loginUrl'=>array('instructor/site/login'),
        ]);

        Yii::$app->view->theme = new Theme([
            'pathMap' => ['@app/views' => '@app/themes/instructor/views'],
            'baseUrl' => '@web/themes/instructor',
        ]);
    }
}
