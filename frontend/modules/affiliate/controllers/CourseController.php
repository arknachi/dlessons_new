<?php

namespace frontend\modules\affiliate\controllers;

use common\models\DbSchedules;
use common\models\DbSchedulesSearch;
use common\models\DlLessons;
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
      public function actionView($id) {
        
        $students_info = DlStudentCourse::find()->Where(['scr_id' =>$id])->one();
         
         $searchModel = new DbSchedulesSearch();
        $dataProvider = $searchModel->courselist(Yii::$app->request->queryParams,$id);
        $stud_info = DlStudent::find()->Where(['student_id' => $students_info->student_id,])->one();

        $les_info = DlLessons::find()->Where(['lesson_id' => $students_info->lesson_id,])->one();

        $total_hours = $les_info->hours;

        $remainings = round(DbSchedules::find()->select('hours')->where('scr_id = :tour_id and scr_completed_status != :id and isDeleted = :delval', ['tour_id' => $id, 'id' => 2, 'delval' => '0'])->sum('hours'));
        $different = abs($total_hours - $remainings);


        return $this->render('view', [
                    'stud_info' => $stud_info,
                    'les_info' => $les_info,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'different' => $different,
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
