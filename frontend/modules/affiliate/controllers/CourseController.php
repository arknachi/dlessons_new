<?php

namespace frontend\modules\affiliate\controllers;

use common\models\DlStudent;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `affiliate` module
 */
class CourseController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $model = $this->findStudent(Yii::$app->user->identity->student_id);

        return $this->render('index', compact('model'));
      }

    protected function findStudent($id) {
        if (($model = DlStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The ad does not exist.');
        }
    }

}
