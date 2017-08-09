<?php

namespace backend\modules\instructor\controllers;

use common\models\DbSchedules;
use common\models\DbSchedulesSearch;
use common\models\DlLessons;
use common\models\DlStudentCourse;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SchedulesController implements the CRUD actions for DbSchedules model.
 */
class SchedulesController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view', 'scheduledstudents', 'statusupdate', 'unassignpaidstud', 'chckscheduleexist', 'schedulehoursexist'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionStatusupdate() {
        if (Yii::$app->request->isAjax) {
            $scrid = $_POST['scrid'];
            $smodel = DlStudentCourse::findOne($scrid);
            $smodel->scr_completed_status = 1;
            $smodel->scr_completed_date = date("Y-m-d", time());
            $smodel->save();
            echo $scrid;
            exit;
        }
    }

    /**
     * Lists all DbSchedules models.
     * @return mixed
     */
    public function actionIndex() {
        $insid = Yii::$app->user->getId();
        $searchModel = new DbSchedulesSearch();
        $dataProvider = $searchModel->Ins_schedules_search(Yii::$app->request->queryParams, $insid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionScheduledstudents($id) {

        $schedulemodel = $this->findModel($id);
        $admid = $schedulemodel->admin_id;
        $lesson_id = $schedulemodel->lesson_id;

        //$getStudents = new DlStudentCourse();
        // $students_provider = $getStudents->student_courses($lesson_id, $admid,$id);

        $scrsmodel = DlStudentCourse::find()->where([
                    'lesson_id' => $lesson_id,
                    'admin_id' => $admid,
                    'scr_paid_status' => 1,
             'scr_id' => $schedulemodel->scr_id,
                ])->one();

        return $this->render('schedule_students', [
                    'model' => $this->findModel($id),
                    'scrsmodel' => $scrsmodel,
                        //'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single DbSchedules model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DbSchedules model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        if (Yii::$app->user->identity->accessschedule) {
            $session = Yii::$app->session;
            $model = new DbSchedules();
            $model->setScenario('create');

            if ($model->load(Yii::$app->request->post())) {
                $model->admin_id = Yii::$app->user->identity->adminid;
                $model->schedule_date = (isset($model->schedule_date)) ? Yii::$app->formatter->asDate($model->schedule_date, 'php:Y-m-d') : "";
                $model->start_time = date('H:i:s', strtotime($model->start_time));
                $model->end_time = date('H:i:s', strtotime($model->end_time));
                $model->isDeleted = 0;
                $model->city_id = Yii::$app->user->identity->inscityid;
                $time1 = strtotime($model->start_time);
                $time2 = strtotime($model->end_time);
                $model->hours = round(abs($time2 - $time1) / 3600);
                if ($model->validate()) {
                    $stdcrsid = $model->stdcrsid;
                    $model->scr_id=$stdcrsid;
                    $model->location_id = ($model->schedule_type == 2) ? $model->location_id : 0;
                    $model->created_by = Yii::$app->user->identity->id;
                    $model->created_at = date("Y-m-d H:i:s");
                    $model->updated_at = date("Y-m-d H:i:s");
                    $model->role = 2;
                  
                    /* Add the schedule and Assign the schedule for the student course */
                    if ($model->save() && $stdcrsid != "") {

//                        $schedule_id = $model->schedule_id;
                        $crsmodel = DlStudentCourse::findOne($stdcrsid);
//                        $crsmodel->schedule_id = $schedule_id;
                        $crsmodel->save();
                    }

                    \Yii::$app->getSession()->setFlash('success', 'Schedule infos added successfully!!!');
                    return $this->redirect(['index']);
                }
            }

            $schedule_date = Yii::$app->getRequest()->getQueryParam('schedule_date');
            if ($schedule_date) {
                $model->schedule_date = $schedule_date;
            }

            $lesson_id = Yii::$app->getRequest()->getQueryParam('lesson_id');
            if ($lesson_id) {
                $model->lesson_id = $lesson_id;
            }

            $param_scr_id = Yii::$app->getRequest()->getQueryParam('scr_id');
            if ($param_scr_id) {
                $model->stdcrsid = $param_scr_id;
            }

            if (!$model->status)
                $model->status = 1;


            return $this->render('create', [
                        'model' => $model
            ]);
        }else {
            \Yii::$app->getSession()->setFlash('danger', 'You have no access to create schedule!!please contact admin.');
            return $this->redirect(['index']);
        }
    }

    public function actionUnassignpaidstud() {
        if (Yii::$app->request->isAjax) {

            $lesson_id = $_POST['id'];

            if (isset(Yii::$app->user->identity->adminid) && $lesson_id != "") {
                $adminid = Yii::$app->user->identity->adminid;
                $query = DlStudentCourse::find()->andWhere([
                            'lesson_id' => $lesson_id,
                            'admin_id' => $adminid,
                            'scr_paid_status' => "1",
                            'scr_completed_status' => '0',
                        ])->all();

                $students_list = ArrayHelper::map($query, "scr_id", function($model, $defaultValue) {
                            return $model->studentinfo;
                        });

                if (!empty($students_list)) {
                    $slist = "<option value=''>---Please select any student---</option>";
                    foreach ($students_list as $key => $sinfo) {
                        $slist .= "<option value='" . $key . "'>" . $sinfo . "</option>";
                    }
                } else {
                    $slist = "<option value=''>No Records</option>";
                }

                echo $slist;
                exit;
            }
        }
    }
     public function actionSchedulehoursexist() {
        $data['leadsCount'] = 0;
       $stdcrsid= $_POST['DbSchedules']['stdcrsid'];
        $lesson_id= $_POST['DbSchedules']['lesson_id'];
        if($lesson_id != '' && $stdcrsid != ''){
            $total_scheduled = round(DbSchedules::find()->select('hours')->where('scr_id = :tour_id and scr_completed_status != :id  and isDeleted = :delval', ['tour_id' => $stdcrsid, 'id' => 2, 'delval' => '0'])->sum('hours'));
            $les_info = DlLessons::find()->select('hours')->Where(['lesson_id' => $lesson_id])->one();
            $total_lessonhours = $les_info->hours; 
            $remainings = $total_lessonhours - $total_scheduled;
                  $start_time = abs(date('H:i:s', strtotime($_POST['DbSchedules']['start_time'])));
                  $end_time = abs(date('H:i:s', strtotime($_POST['DbSchedules']['end_time'])));
                   $different = $end_time - $start_time;
                   if($remainings >= $different){
                         $data['leadsCount'] = 1;
                   }
        }  else {
       $crsmodel = DbSchedules::findOne($_POST['schedule_id']);
       $total_scheduled = round(DbSchedules::find()->select('hours')->where('scr_id = :tour_id and scr_completed_status != :id  and isDeleted = :delval', ['tour_id' => $crsmodel ['scr_id'], 'id' => 2, 'delval' => '0'])->sum('hours'));
       $les_info = DlLessons::find()->select('hours')->Where(['lesson_id' => $crsmodel ['lesson_id']])->one();
            $total_lessonhours = $les_info->hours; 
            $remainings = $total_lessonhours - $total_scheduled;
                    $start_time = abs(date('H:i:s', strtotime($_POST['DbSchedules']['start_time'])));
                  $end_time = abs(date('H:i:s', strtotime($_POST['DbSchedules']['end_time'])));
                   $different = $end_time - $start_time;
                   if($remainings >= $different){
                         $data['leadsCount'] = 1;
                   }
              }
             
                 
//            if(isset($_POST['scr_id'])){
//                $current_scheduled_time = round(DbSchedules::find()->select('hours')->where('schedule_id = :tour_id', ['tour_id' => $_POST['schedule_id']])->sum('hours'));
//                 
//                foreach ($_POST['DbSchedules'] as $key=>$modelinfo) {
//                    if($key == 'start_time'){
//                        $start_time = abs(date('H:i:s', strtotime($modelinfo)));
//                    }
//                     if($key == 'end_time'){
//                        $end_time = abs(date('H:i:s', strtotime($modelinfo)));
//                    }
//
//                }
//                $different = $end_time - $start_time;
//                $summ = round($different - $current_scheduled_time);
//            }else{
//                foreach ($_POST['DbSchedules'] as $modelinfo) {
//                        $start_time = abs(date('H:i:s', strtotime($modelinfo['start_time'])));
//                        $end_time = abs(date('H:i:s', strtotime($modelinfo['end_time'])));
//                        $different = $end_time - $start_time;
//                        $summ += $different;
//
//                }
//            }
//            
//            $c_hours = $summ + $total_scheduled;
//            
//            $data['lessonhours'] = $total_lessonhours;
//            $data['scheduled'] = $total_scheduled;
//            $data['remaining'] = $remainings;
//            $data['formhour'] = $summ;
//            $data['c_hours'] = $c_hours;
//            
//
//            if ($total_lessonhours >= $c_hours ) {
//                $data['leadsCount'] = 1;
//            }
//        }else{
//            $data['leadsCount'] = 2;
//        }
        echo json_encode($data);
        exit;
    }


    public function actionChckscheduleexist() {
        $data['leadsCount'] = 0;

        $admin_id = Yii::$app->user->identity->adminid;
        $ins_id = isset($_POST['DbSchedules']['instructor_id']) ? $_POST['DbSchedules']['instructor_id'] : "";
        $schedule_date = isset($_POST['DbSchedules']['schedule_date']) ? Yii::$app->formatter->asDate($_POST['DbSchedules']['schedule_date'], 'php:Y-m-d') : "";
        $start_time = isset($_POST['DbSchedules']['start_time']) ? date('H:i:s', strtotime($_POST['DbSchedules']['start_time'])) : "";
        $end_time = isset($_POST['DbSchedules']['end_time']) ? date('H:i:s', strtotime($_POST['DbSchedules']['end_time'])) : "";

        if ($ins_id != "" && $schedule_date != "" && $start_time != "" && $end_time != "") {

            $sdtime = $schedule_date . " " . $start_time;
            $edtime = $schedule_date . " " . $end_time;

            $schedule_id = isset($_POST['schedule_id']) ? $_POST['schedule_id'] : "";
            if ($schedule_id != "") {

                $leadsCount = DbSchedules::find()
                        ->select(['COUNT(*) AS cnt'])
                        ->andWhere('schedule_id!="' . $schedule_id . '" and instructor_id="' . $ins_id . '" 
                        AND
                        ( 
                            ("' . $sdtime . '"  >= TIMESTAMP(schedule_date, start_time) &&  "' . $sdtime . '"  <= TIMESTAMP(schedule_date, end_time))
                            OR 
                            ("' . $edtime . '"  >= TIMESTAMP(schedule_date, start_time) &&  "' . $edtime . '"  <= TIMESTAMP(schedule_date, end_time))
                        )')
                        ->count();
            } else {
                $leadsCount = DbSchedules::find()
                        ->select(['COUNT(*) AS cnt'])
                        ->andWhere('instructor_id="' . $ins_id . '" 
                        AND
                        ( 
                            ("' . $sdtime . '"  >= TIMESTAMP(schedule_date, start_time) &&  "' . $sdtime . '"  <= TIMESTAMP(schedule_date, end_time))
                            OR 
                            ("' . $edtime . '"  >= TIMESTAMP(schedule_date, start_time) &&  "' . $edtime . '"  <= TIMESTAMP(schedule_date, end_time))
                        )')
                        ->count();
            }
            $data['leadsCount'] = $leadsCount;
        }
        echo json_encode($data);
        exit;
    }

    /**
     * Updates an existing DbSchedules model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        if (Yii::$app->user->identity->accessschedule) {
            $model = $this->findModel($id);

            $insid = Yii::$app->user->getId();
            if ($model->role == 2) {
                if ($model->created_by != $insid) {
                    \Yii::$app->getSession()->setFlash('danger', 'You have no permission to modify that schedule!!!');
                    return $this->redirect(['index']);
                }
            } else {
                \Yii::$app->getSession()->setFlash('danger', 'You have no permission to modify that schedule!!!');
                return $this->redirect(['index']);
            }

            if ($model->load(Yii::$app->request->post())) {

                $model->admin_id = Yii::$app->user->identity->adminid;
                $model->schedule_date = (isset($model->schedule_date)) ? Yii::$app->formatter->asDate($model->schedule_date, 'php:Y-m-d') : "";
                $model->start_time = date('H:i:s', strtotime($model->start_time));
                $model->end_time = date('H:i:s', strtotime($model->end_time));
                 $time1 = strtotime($model->start_time);
                $time2 = strtotime($model->end_time);
                $model->hours = round(abs($time2 - $time1) / 3600);
                if ($model->validate()) {
                    $stdcrsid = $model->stdcrsid;

                    $model->location_id = ($model->schedule_type == 2) ? $model->location_id : 0;
                    $model->updated_by = Yii::$app->user->identity->id;
                    $model->updated_at = date("Y-m-d H:i:s");
                    $model->save();

                    if ($stdcrsid != "") {
//                        $schedule_id = $model->schedule_id;

//                        DlStudentCourse::updateAll(['schedule_id' => 0], "schedule_id = '" . $schedule_id . "'");

                        $crsmodel = DlStudentCourse::findOne($stdcrsid);
//                        $crsmodel->schedule_id = $schedule_id;
                        $crsmodel->save();
                    }

                    \Yii::$app->getSession()->setFlash('success', 'Schedule infos updated successfully!!!');
                    return $this->redirect(['index']);
                } else {
                    //print_r($model->errors);exit;
                }
            }

            return $this->render('update', [
                        'model' => $model,
            ]);
        } else {
            \Yii::$app->getSession()->setFlash('danger', 'You have no access to create schedule!!please contact admin.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing DbSchedules model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {

        $model = $this->findModel($id);

        $insid = Yii::$app->user->getId();
        if ($model->role == 2) {
            if ($model->created_by != $insid) {
                \Yii::$app->getSession()->setFlash('danger', 'You have no permission to modify that schedule!!!');
                return $this->redirect(['index']);
            }
        } else {
            \Yii::$app->getSession()->setFlash('danger', 'You have no permission to modify that schedule!!!');
            return $this->redirect(['index']);
        }

        DlStudentCourse::updateAll(['schedule_id' => 0], "schedule_id = '" . $id . "'");
        $model->delete();

        \Yii::$app->getSession()->setFlash('success', 'Schedule infos deleted successfully!!!');
        return $this->redirect(['index']);
    }

    /**
     * Finds the DbSchedules model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DbSchedules the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DbSchedules::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
