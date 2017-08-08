<?php

namespace backend\modules\instructor\controllers;

use common\models\DbSchedules;
use common\models\DlInstructors;
use Yii;
use yii2fullcalendar\models\Event as Event2;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `instructor` module
 */
class DefaultController extends Controller {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'changepassword', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                // 'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $schedule_count = DbSchedules::find()->where(['instructor_id' => Yii::$app->user->getId()])->count();
       
//        $adminid=Yii::$app->user->identity->adminid;
        /* Calender Display */
        $students_schedules = DbSchedules::find()->where([
//                   'admin_id' => $adminid,
                    'status' => 1, 
            'instructor_id' => Yii::$app->user->getId(),
                ])->andWhere(['!=', 'schedule_id', 0])->all();
//        echo '<pre>';print_r($students_schedules);exit;
        $events = array();

        foreach ($students_schedules AS $sinfo) {
            if(isset($sinfo->dlStudentCourses)){
            $sch_strt = $sinfo->schedule_date . ' ' . $sinfo->start_time;
            $sch_end = $sinfo->schedule_date . ' ' . $sinfo->end_time;

            $stime = date('h:i a', strtotime($sinfo->start_time));
            $etime = date('h:i a', strtotime($sinfo->end_time));
            
            $Event = new Event2();
            $Event->id = $sinfo->schedule_id;
            $Event->title = $sinfo->dlStudentCourses->Studentname."<br> ".$stime." - ".$etime;
            $Event->description = $sinfo->dlStudentCourses->scheduleinfo;
            $Event->start = $sch_strt;
            $Event->end = $sch_end;
            $Event->color = '#932AB6';
            $Event->url = Url::toRoute(['schedules/scheduledstudents', 'id' => $sinfo->schedule_id]);
            $events[] = $Event;
            }
        }

        return $this->render('index', [
                    'schedule_count' => $schedule_count,
                    'events' => $events
        ]);
    }

    /**
     * Renders the changepassword view for the module
     * @return string
     */
    public function actionChangepassword() {
        $model = $this->findModel(Yii::$app->user->getId());
        $model->scenario = 'changepassword';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $pwd_details = Yii::$app->request->post();

            $model->password = $pwd_details['DlInstructors']['newpass'];
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Changed the password successfully!!!');
            $this->redirect(array('/instructor/default/changepassword'));
        } else {
            return $this->render('changepassword', [
                        'model' => $model,
            ]);
        }
    }

    public function actionProfile() {

        $model = $this->findModel(Yii::$app->user->getId());

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Profile updated successfully!!!');
            return $this->render('updateprofile', [
                        'model' => $model,
            ]);
        } else {

            return $this->render('updateprofile', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Finds the DlInstructors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DlInstructors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DlInstructors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
