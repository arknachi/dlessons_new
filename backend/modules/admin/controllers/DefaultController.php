<?php

namespace backend\modules\admin\controllers;

use common\models\DbSchedules;
use common\models\DlAdmin;
use common\models\DlAdminLessons;
use common\models\DlInsAvailableDays;
use common\models\DlInstructors;
use common\models\DlSettings;
use Yii;
use yii2fullcalendar\models\Event as Event2;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
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

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $adminid = Yii::$app->user->identity->ParentAdminId;

        $lessons_count = DlAdminLessons::find()->where(['admin_id' => $adminid])->count();
        $ins_count = DlInstructors::find()->count();
        //$student_count = DlStudent::find()->where(['admin_id' => $adminid])->count();
        $admin_model = DlAdmin::find()->where(['admin_id' => $adminid])->one();
        $student_count = $admin_model->getMystudents()->count();
        $schedule_count = DbSchedules::find()->count();

        /* Calender Display */
        $students_schedules = DbSchedules::find()->where([
                    'admin_id' => $adminid,
                    'status' => 1, 
                ])->andWhere(['!=', 'schedule_id', 0])->all();

        $events = array();

        foreach ($students_schedules AS $sinfo) {
           
            if(isset($sinfo->dlStudentCourses)){
                $sch_strt = $sinfo->schedule_date . ' ' . $sinfo->start_time;
              
                $sch_end = $sinfo->schedule_date . ' ' . $sinfo->end_time;
                $stime = date('h:i a', strtotime($sinfo->start_time));
                $etime = date('h:i a', strtotime($sinfo->end_time));
                $Event = new Event2();
                $Event->id = "Stud" . $sinfo->schedule_id;
                $Event->title = "<div class='sched_info'>Stud: " . $sinfo->dlStudentCourses->Studentname . "<br> Ins: " . $sinfo->dlStudentCourses->Instructorname . "<br> " . $stime . " - " . $etime."</div>";
                $Event->description = $sinfo->dlStudentCourses->scheduleinfo;
                $Event->start = $sch_strt;
                $Event->end = $sch_end;
                $Event->color = '#932AB6';
                $Event->url = Url::toRoute(['schedules/scheduledstudents', 'id' => $sinfo->schedule_id]);
                $events[] = $Event;
            }
        }

        /* Ins Free Times */
        $ins_schedules = DlInsAvailableDays::find()->all();
        
        if(!empty($ins_schedules)){
            
            foreach ($ins_schedules AS $sinfo) {
                
            if(isset($sinfo->instructor)){
                
                    $available_date = $sinfo->available_date;
                    $schedules_list = array();
                    $schedules_list = DbSchedules::find()->andWhere([                              
                                'schedule_date' => $available_date,
                                'instructor_id' => $sinfo->instructor_id,
                                'status' => 1,                               
                            ])->orderBy(['end_time' => SORT_DESC])->one();

                    $etime = date('h:i a', strtotime($sinfo->end_time));
                    $Event = new Event2();
                    $Event->id = "Ins" . $sinfo->available_id;           
                    if($schedules_list)
                    {     
                        $stime = date('h:i a', strtotime($schedules_list->end_time));

                        $endTime = strtotime("+1 minute", strtotime($stime));
                        $disp_stime = date('h:i a', $endTime);

                        $Event->title = "<div class='sched_info'>".$sinfo->instructor->first_name . " " . $sinfo->instructor->last_name . "<br> " . $disp_stime . " - " . $etime."</div>";
                        $Event->start = $sinfo->available_date . ' ' . $schedules_list->end_time;
                    }else{
                        $stime = date('h:i a', strtotime($sinfo->start_time));              
                        $Event->title = "<div class='sched_info'>".$sinfo->instructor->first_name . " " . $sinfo->instructor->last_name . "<br> " . $stime . " - " . $etime."</div>";
                        $Event->start = $sinfo->available_date . ' ' . $sinfo->start_time;
                    }           
                    $Event->end = $sinfo->available_date . ' ' . $sinfo->end_time;
                    $Event->color = '#00A65A';
                    $Event->url = Url::toRoute(['schedules/create', 'available_id' => $sinfo->available_id]);

                    if($stime==$etime)
                        continue;

                    $events[] = $Event;
                }
           }
        }
        
        /* Dashboard setting */
        $setting_model = DlSettings::find()->where(["option_type"=>'dashboard',"admin_id"=>Yii::$app->user->identity->ParentAdminId])->one();          

//    
        return $this->render('index', [
                    'lessons_count' => $lessons_count,
                    'ins_count' => $ins_count,
                    'student_count' => $student_count,
                    'schedule_count' => $schedule_count,
                    'events' => $events,
                    'setting_model' => $setting_model
        ]);
    }

//    public function actionJsoncalendar($start = NULL, $end = NULL, $_ = NULL) {
//        $times = DbSchedules::find()->all();
//
//        $events = array();
//
//        foreach ($times AS $time) {
//            //Testing
//            $Event = new Event2();
//            ;
//            $Event->id = $time->id;
//            $Event->title = $time->categoryAsString;
//            $Event->start = date('Y-m-d\Th:m:s\Z', strtotime($time->date_start . ' ' . $time->time_start));
//            $Event->end = date('Y-m-d\Th:m:s\Z', strtotime($time->date_start . ' ' . $time->time_end));
//            $events[] = $Event;
//        }
//
//        header('Content-type: application/json');
//        echo Json::encode($events);
//
//        Yii::$app->end();
//    }

    /**
     * Renders the changepassword view for the module
     * @return string
     */
    public function actionChangepassword() {
        $model = $this->findModel(Yii::$app->user->getId());
        $model->scenario = 'changepassword';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $pwd_details = Yii::$app->request->post();

            $model->password = $pwd_details['DlAdmin']['newpass'];
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Changed the password successfully!!!');
            $this->redirect(array('/admin/default/changepassword'));
        } else {
            return $this->render('changepassword', [
                        'model' => $model,
            ]);
        }
    }

    public function actionProfile() {

        $model = $this->findModel(Yii::$app->user->getId());

        if (Yii::$app->request->post() && $load_request = $model->load(Yii::$app->request->post())) {
            unset($model->password);
            if ($load_request && $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Profile updated successfully!!!');
                return $this->render('updateprofile', [
                            'model' => $model,
                ]);
            }
        }

        return $this->render('updateprofile', [
                    'model' => $model,
        ]);
    }

    /**
     * Finds the DlAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DlAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DlAdmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
