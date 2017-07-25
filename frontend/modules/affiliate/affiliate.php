<?php

namespace frontend\modules\affiliate;

use Yii;
use yii\base\Module;

/**
 * affiliate module definition class
 */
class affiliate extends Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\affiliate\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->layout = "@app/modules/affiliate/views/layouts/main";

        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\DlStudent',
            'enableAutoLogin' => true,
            // 'loginUrl' => ['yonetim/default/login'],
            'identityCookie' => ['name' => 'student', 'httpOnly' => true],
            //  'idParam' => 'editor_id', //this is important !
            'returnUrl' => array('affiliate/user/index'),
            'loginUrl' => array('affiliate/default/login'),
        ]);
    }

}
