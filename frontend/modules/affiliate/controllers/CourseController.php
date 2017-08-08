<?php

namespace frontend\modules\affiliate\controllers;

use common\models\DbSchedulesSearch;
use common\models\DlStudent;
use common\models\DlStudentCourse;
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
                        'actions' => ['index','view'],
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
      public function actionView() {
          
        $students_info = DlStudentCourse::find()->Where(['student_id' => Yii::$app->user->identity->student_id])->one();
         $searchModel = new DbSchedulesSearch();
        $dataProvider = $searchModel->courselist(Yii::$app->request->queryParams, $students_info->scr_id);
        return $this->render('view', [
                    'dataProvider' => $dataProvider,
        ]);
        
        return $this->render('view', compact('model'));
      }

    protected function findStudent($id) {
        if (($model = DlStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The ad does not exist.');
        }
    }

}
