<?php

namespace backend\modules\admin\controllers;

use common\models\DbSchedules;
use common\models\DbSchedulesSearch;
use common\models\DlInsAvailableDays;
use common\models\DlInstructors;
use common\models\DlLessons;
use common\models\DlStudent;
use common\models\DlStudentCourse;
use Yii;
use yii2fullcalendar\models\Event;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'deleteall', 'scheduledstudents', 'unassignpaidstud', 'ins_schedule_info', 'available_ins_info', 'chckscheduleexist', 'schedulehoursexist', 'statusupdate'],
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

    /**
     * Lists all DbSchedules models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DbSchedulesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
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

    public function actionUnassignpaidstud() {
        if (Yii::$app->request->isAjax) {

            $lesson_id = $_POST['id'];
            if (isset(Yii::$app->user->identity->ParentAdminId) && $lesson_id != "") {
                $adminid = Yii::$app->user->identity->ParentAdminId;
                $querys = DlStudentCourse::find()->andWhere([
                            'lesson_id' => $lesson_id,
                            'admin_id' => $adminid,
                            'scr_paid_status' => '1',
                            'scr_completed_status' => '0',
                        ])->all();

                //Check Avalilable Schedules
                $avaliable = array();
                foreach ($querys as $query) {

                    $les_info = DlLessons::find()->Where(['lesson_id' => $lesson_id,])->one();
                    $total_hours = $les_info->hours;
                    $remainings = round(DbSchedules::find()->select('hours')->where('scr_id = :tour_id and scr_completed_status != :id  and isDeleted = :delval', ['tour_id' => $query->scr_id, 'id' => 2, 'delval' => '0'])->sum('hours'));
                    $different = abs($total_hours - $remainings);

                    if ($different > 0) {
                        $avaliable[] = $query->scr_id;
                    }
                }

                $querys_new = DlStudentCourse::find()
                                ->where(['in', 'scr_id', $avaliable])
                                ->andWhere([
                                    'lesson_id' => $lesson_id,
                                    'admin_id' => $adminid,
                                    'scr_paid_status' => '1',
                                    'scr_completed_status' => '0',
                                ])->all();

                $students_list = ArrayHelper::map($querys_new, "scr_id", function($model, $defaultValue) {
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

    public function actionIns_schedule_info() {
        if (Yii::$app->request->isAjax) {
            $data = array();
            $ins_id = $_POST['ins_id'];
            $schedule_date = $_POST['sch_date'];


            $schedule_date = Yii::$app->formatter->asDate($schedule_date, 'php:Y-m-d');

            $crsmodel = DlInsAvailableDays::find()->where([
                        'instructor_id' => $ins_id,
                        'available_date' => $schedule_date,
                    ])->one();
            if ($crsmodel) {

                /* Check the existing start time in the schedule list */
                $schedules_list = DbSchedules::find()->andWhere([
                            'schedule_date' => $schedule_date,
                            'instructor_id' => $ins_id,
                            'status' => 1,
                        ])->orderBy(['end_time' => SORT_DESC])->one();
                $data['available_date'] = Yii::$app->formatter->asDate($crsmodel->available_date, 'php:m/d/Y');
                if ($schedules_list) {
                    $strtTime = strtotime("+1 minute", strtotime($schedules_list->end_time));
                    $data['start_time'] = date('h:i a', $strtTime);
                } else {
                    $data['start_time'] = date('h:i a', strtotime($crsmodel->start_time));
                }
                $data['end_time'] = date('h:i a', strtotime($crsmodel->end_time));
                $data['instructor_id'] = $crsmodel->instructor_id;

                echo json_encode($data);
                exit;
            }
        }
    }

    public function fetch_available_instructors($selecteddate) {
        $instructors = array();

        /* Get other instructors in the available date */
        $av_ins = DlInstructors::find()->joinWith('insavailabledays')->andWhere([
                    'dl_ins_available_days.available_date' => $selecteddate,
                ])->all();

        if ($av_ins) {
            foreach ($av_ins AS $insinfo) {

                $schedules_list1 = array();
                $stime = "";

                $schedules_list1 = DbSchedules::find()->andWhere([
                            'schedule_date' => $selecteddate,
                            'instructor_id' => $insinfo->instructor_id,
                            'status' => 1,
                        ])->orderBy(['end_time' => SORT_DESC])->one();

                if ($schedules_list1) {
                    $stime = date('h:i a', strtotime($schedules_list1->end_time));
                }
                $etime = date('h:i a', strtotime($insinfo->insavailabledays[0]->end_time));

                if ($stime != "" && $stime == $etime)
                    continue;

                $instructors[$insinfo->instructor_id] = $insinfo->first_name . " " . $insinfo->last_name;
            }
        }

        return $instructors;
    }

    public function actionAvailable_ins_info() {
        if (Yii::$app->request->isAjax) {
            $selecteddate = Yii::$app->formatter->asDate($_POST['selecteddate'], 'php:Y-m-d');
            $instructors = $this->fetch_available_instructors($selecteddate);
            if (!empty($instructors)) {
                $slist = "<option value=''>---Select Instructor---</option>";
                foreach ($instructors as $key => $sinfo) {
                    $slist .= "<option value='" . $key . "'>" . $sinfo . "</option>";
                }
            } else {
                $slist = "<option value=''>---Select Instructor---</option>";
            }
            echo $slist;
            exit;
        }
    }

    /*
      public function actionAssignstudents($id) {

      $schedulemodel = $this->findModel($id);
      $adminid     = $schedulemodel->admin_id;
      $lesson_id   = $schedulemodel->lesson_id;

      $getStudents = new DlStudentCourse();
      $students_provider = $getStudents->student_courses($lesson_id, $adminid);

      if (Yii::$app->request->post('selection')) {
      $selection = (array) Yii::$app->request->post('selection'); //typecasting
      foreach ($selection as $scr_id) {
      if (($std_course = DlStudentCourse::findOne($scr_id)) !== null) {
      $std_course->schedule_id = $id;
      $std_course->save();
      }
      }

      \Yii::$app->getSession()->setFlash('success', 'Assigned students successfully for the schedule!!!');
      return $this->redirect(['index']);
      }

      return $this->render('assign_students', [
      'model' => $this->findModel($id),
      'dataProvider' => $students_provider
      ]);
      }


      public function actionScheduledstudents($id) {

      $schedulemodel = $this->findModel($id);
      $adminid     = $schedulemodel->admin_id;
      $lesson_id   = $schedulemodel->lesson_id;

      $getStudents = new DlStudentCourse();
      $students_provider = $getStudents->student_courses($lesson_id, $adminid,$id);

      return $this->render('schedule_students', [
      'model' => $this->findModel($id),
      'dataProvider' => $students_provider
      ]);
      }
     */

    public function actionScheduledstudents($id) {

        $schedulemodel = $this->findModel($id);

        $admid = $schedulemodel->admin_id;

        $lesson_id = $schedulemodel->lesson_id;

        $scrsmodel = DlStudentCourse::find()->where([
                    'lesson_id' => $lesson_id,
                    'admin_id' => $admid,
                    'scr_paid_status' => 1,
                    'scr_id' => $schedulemodel->scr_id,
                ])->one();
        return $this->render('schedule_students', [
                    'model' => $this->findModel($id),
                    'scrsmodel' => $scrsmodel,
        ]);
    }

    /**
     * Displays a single DbSchedules model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
//        $model = DbSchedules::find()->where(['scr_id'=>$id])->all();     

        $searchModel = new DbSchedulesSearch();
        $dataProvider = $searchModel->searchlist(Yii::$app->request->queryParams, $id);

        $students_info = DlStudentCourse::find()->Where(['scr_id' => $id])->one();

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
                    'std_scr_id' => $id,
                    'crs_status' => $students_info->scr_completed_date
        ]);
    }

    /**
     * Creates a new DbSchedules model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $session = Yii::$app->session;
        $model = new DbSchedules();
        $modelsschedules = [new DbSchedules];
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post())) {
            $models = Yii::$app->request->post();

            $lesson_id = $models['DbSchedules']['lesson_id'];
            unset($models['DbSchedules']['lesson_id']);
            $stdcrsid = $models['DbSchedules']['stdcrsid'];
            unset($models['DbSchedules']['stdcrsid']);
//            $location_id = $models['DbSchedules']['location_id'];


            foreach ($models['DbSchedules'] as $modelinfo) {
                $model = new DbSchedules();

                $model->admin_id = Yii::$app->user->identity->ParentAdminId;
                $root = Yii::$app->formatter->asDate($modelinfo['schedule_date'], 'php:Y-m-d');
                $model->schedule_date = (isset($modelinfo['schedule_date'])) ? $root : "";
                $model->instructor_id = $modelinfo ['instructor_id'];
                $model->start_time = date('H:i:s', strtotime($modelinfo ['start_time']));
                $model->end_time = date('H:i:s', strtotime($modelinfo ['end_time']));
                $time1 = strtotime($model->start_time);
                $time2 = strtotime($model->end_time);

                $model->hours = round(abs($time2 - $time1) / 3600);

                $model->schedule_type = $modelinfo ['schedule_type'];
                $model->location_id = $modelinfo['location_id'];
                $model->isDeleted = 0;
                if ($session->has('cityid')) {
                    $model->city_id = $session->get('cityid');
                }
                $model->lesson_id = $lesson_id;
                $model->scr_id = $stdcrsid;
                if ($model->validate()) {
                    $model->location_id = ($model->schedule_type == 2) ? $model->location_id : 0;
                    $model->created_by = Yii::$app->user->identity->id;
                    $model->created_at = date("Y-m-d H:i:s");
                    $model->updated_at = date("Y-m-d H:i:s");
                    $model->save();
                }
            }
            /* Add the schedule and Assign the schedule for the student course */

            if ($stdcrsid != "") {
                $crsmodel = DlStudentCourse::findOne($stdcrsid);
                $crsmodel->save();
            }

            \Yii::$app->getSession()->setFlash('success', 'Schedule infos added successfully!!!');
            return $this->redirect(['index']);
        }

        $instructors = ArrayHelper::map(
                        DlInstructors::find()->all(), 'id', function($model, $defaultValue) {
                    return $model->first_name . " " . $model->last_name;
                });

        $schedule_date = Yii::$app->getRequest()->getQueryParam('schedule_date');
        if ($schedule_date) {
            $model->schedule_date = $schedule_date;

            /* Get instructors in the available date */
            $instructors = $this->fetch_available_instructors($schedule_date);
        }

        $lesson_id = Yii::$app->getRequest()->getQueryParam('lesson_id');
        if ($lesson_id) {
            $model->lesson_id = $lesson_id;
        }

        $param_scr_id = Yii::$app->getRequest()->getQueryParam('scr_id');
        if ($param_scr_id) {
            $model->stdcrsid = $param_scr_id;
        }

        /* Available instructors */
        $available_id = Yii::$app->getRequest()->getQueryParam('available_id');
        if ($available_id) {

            $crsmodel = DlInsAvailableDays::findOne($available_id);
            $model->schedule_date = $crsmodel->available_date;

            /* Check the existing start time in the schedule list and set the start and end time value */
            $schedules_list = DbSchedules::find()->andWhere([
                        'schedule_date' => $crsmodel->available_date,
                        'instructor_id' => $crsmodel->instructor_id,
                        'status' => 1,
                    ])->orderBy(['end_time' => SORT_DESC])->one();

            if ($schedules_list) {
                $model->start_time = date('h:i a', strtotime("+1 minute", strtotime($schedules_list->end_time)));
            } else {
                $model->start_time = $crsmodel->start_time;
            }
            $model->end_time = $crsmodel->end_time;

            $model->instructor_id = $crsmodel->instructor_id;

            /* Get other instructors in the available date */
            $av_ins = DlInsAvailableDays::find()->andWhere('available_date = :available_date', [':available_date' => $crsmodel->available_date])->all();
            foreach ($av_ins AS $insinfo) {
                $schedules_list1 = array();
                $stime = "";

                $schedules_list1 = DbSchedules::find()->andWhere([
                            'schedule_date' => $insinfo->available_date,
                            'instructor_id' => $insinfo->instructor_id,
                            'status' => 1,
                        ])->orderBy(['end_time' => SORT_DESC])->one();

                if ($schedules_list1) {
                    $stime = date('h:i a', strtotime($schedules_list1->end_time));
                }

                $etime = date('h:i a', strtotime($insinfo->end_time));

                if ($stime != "" && $stime == $etime)
                    continue;

                $available_instructors[$insinfo->instructor_id] = $insinfo->instructor_id;
            }

            $instructors = array_intersect_key($instructors, $available_instructors);
        }

        if (!$model->status)
            $model->status = 1;

        /* Calender Display */
        $events = array();

        /* Ins Free Times */
        $ins_schedules = DlInsAvailableDays::find()->all();
        foreach ($ins_schedules AS $sinfo) {
            if (isset($sinfo->instructor)) {
                $available_date = $sinfo->available_date;
                $schedules_list = array();
                $schedules_list = DbSchedules::find()->andWhere([
                            'schedule_date' => $available_date,
                            'instructor_id' => $sinfo->instructor_id,
                        ])->orderBy(['end_time' => SORT_DESC])->one();

                $etime = date('h:i a', strtotime($sinfo->end_time));
                $Event = new Event();
                $Event->id = "Ins" . $sinfo->available_id;
                if ($schedules_list) {
                    $stime = date('h:i a', strtotime($schedules_list->end_time));

                    $endTime = strtotime("+1 minute", strtotime($stime));
                    $disp_stime = date('h:i a', $endTime);

                    $Event->title = "<div class='sched_info'>" . $sinfo->instructor->first_name . " " . $sinfo->instructor->last_name . "<br> " . $disp_stime . " - " . $etime . "</div>";
                    $Event->start = $sinfo->available_date . ' ' . $schedules_list->end_time;
                } else {
                    $stime = date('h:i a', strtotime($sinfo->start_time));
                    $Event->title = "<div class='sched_info'>" . $sinfo->instructor->first_name . " " . $sinfo->instructor->last_name . "<br> " . $stime . " - " . $etime . "</div>";
                    $Event->start = $sinfo->available_date . ' ' . $sinfo->start_time;
                }
                $Event->end = $sinfo->available_date . ' ' . $sinfo->end_time;
                $Event->color = '#00A65A';
                $Event->url = Url::toRoute(['schedules/create', 'available_id' => $sinfo->available_id]);

                if ($stime == $etime)
                    continue;

                $events[] = $Event;
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'modelsschedules' => $modelsschedules,
                    'instructors' => $instructors,
                    'events' => $events
        ]);
    }

    public function actionSchedulehoursexist() {
        $data['leadsCount'] = 0;

        $admin_id = Yii::$app->user->identity->ParentAdminId;
        $lesson_id = $_POST['DbSchedules']['lesson_id'];
        unset($_POST['DbSchedules']['lesson_id']);

        $flag = '0';
        if (isset($_POST['DbSchedules']['stdcrsid']) && $_POST['DbSchedules']['stdcrsid'] != "") {
            $data['stdcrsid'] = $_POST['DbSchedules']['stdcrsid'];
            $stdcrsid = $_POST['DbSchedules']['stdcrsid'];
            unset($_POST['DbSchedules']['stdcrsid']);
            $flag = '1';
        } else if (isset($_POST['scr_id'])) {
            $stdcrsid = $_POST['scr_id'];
            $flag = '2';
        }



        if (isset($_POST['DbSchedules']['schedule_id'])) {
            unset($_POST['DbSchedules']['schedule_id']);
        }

//        $stdcrsid = $_POST['DbSchedules']['stdcrsid'];
//        unset($_POST['DbSchedules']['stdcrsid']);
        $summ = 0;
        if ($lesson_id != '' && $stdcrsid != '') {

            $total_scheduled = round(DbSchedules::find()->select('hours')->where('scr_id = :tour_id and scr_completed_status != :id  and isDeleted = :delval', ['tour_id' => $stdcrsid, 'id' => 2, 'delval' => '0'])->sum('hours'));
            $les_info = DlLessons::find()->select('hours')->Where(['lesson_id' => $lesson_id])->one();
            $total_lessonhours = $les_info->hours;
            $remainings = $total_lessonhours - $total_scheduled;
            if (isset($_POST['scr_id'])) {


                foreach ($_POST['DbSchedules'] as $key => $modelinfo) {
                    if ($key == 'start_time') {
                        $start_time = abs(date('H:i:s', strtotime($modelinfo)));
                    }
                    if ($key == 'end_time') {
                        $end_time = abs(date('H:i:s', strtotime($modelinfo)));
                    }
                }
                $different = $end_time - $start_time;
                $data['different'] = $different;
                if ($flag == '2') {
                    $current_scheduled_time = DbSchedules::find()->select('hours')->where('schedule_id = :tour_id', ['tour_id' => $_POST['schedule_id']])->one();
                    $total_scheduled = round(abs($total_scheduled - $current_scheduled_time->hours));
                    $summ = $different;
                    $remainings = $total_lessonhours - $total_scheduled;
                    $data['current_scheduled_time'] = $current_scheduled_time->hours;
                } else {
                    $summ = $different;
                }
                $data['stdcrsid'] = $stdcrsid;
            } else {
                foreach ($_POST['DbSchedules'] as $modelinfo) {
                    $start_time = abs(date('H:i:s', strtotime($modelinfo['start_time'])));
                    $end_time = abs(date('H:i:s', strtotime($modelinfo['end_time'])));
                    $different = $end_time - $start_time;
                    $summ += $different;
                }
            }

            $c_hours = $summ + $total_scheduled;

            $data['lessonhours'] = $total_lessonhours;
            $data['scheduled'] = $total_scheduled;
            $data['remaining'] = $remainings;
            $data['formhour'] = $summ;
            $data['c_hours'] = $c_hours;


            if ($total_lessonhours >= $c_hours) {
                $data['leadsCount'] = 1;
            }
        } else {
            $data['leadsCount'] = 2;
        }
        echo json_encode($data);
        exit;
    }

    public function actionChckscheduleexist() {
        $data['leadsCount'] = 0;

        $admin_id = Yii::$app->user->identity->ParentAdminId;
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $post = Yii::$app->request->post();
            $status = $post['DbSchedules']['scr_completed_status'];
            $model->admin_id = Yii::$app->user->identity->ParentAdminId;
            $model->schedule_date = (isset($model->schedule_date)) ? Yii::$app->formatter->asDate($model->schedule_date, 'php:Y-m-d') : "";
            $model->start_time = date('H:i:s', strtotime($model->start_time));
            $model->end_time = date('H:i:s', strtotime($model->end_time));
            $time1 = strtotime($model->start_time);
            $time2 = strtotime($model->end_time);
            $model->hours = round(abs($time2 - $time1) / 3600, 2);

            if ($model->validate()) {
                $stdcrsid = $model->stdcrsid;

                $model->location_id = ($model->schedule_type == 2) ? $model->location_id : 0;
                $model->updated_by = Yii::$app->user->identity->id;
                $model->updated_at = date("Y-m-d H:i:s");
                $model->scr_completed_status = $status;

                if ($model->stdcrsid != "") {
                    $model->scr_id = $model->stdcrsid;
                }
                $model->save();
                $les_info = DlLessons::find()->Where(['lesson_id' => $model->lesson_id])->one();
                $total_hours = $les_info->hours;
                $remainings = round(DbSchedules::find()->select('hours')->where('scr_id = :tour_id and scr_completed_status = :id  and isDeleted = :delval', ['tour_id' => $model->scr_id, 'id' => 1, 'delval' => '0'])->sum('hours'));
                $different = abs($total_hours - $remainings);
               
                if ($different == 0) {
                   
                    $crsmodel = DlStudentCourse::findOne($model->scr_id);
                    $crsmodel->scr_completed_status = 1;
                    $crsmodel->scr_completed_date = date("Y-m-d");
                    $crsmodel->save();
//                      print_r($different);
//                exit;
                }
//                if ($stdcrsid != "") {
//                    $schedule_id = $model->schedule_id;
//
//                    DlStudentCourse::updateAll(['schedule_id' => 0], "schedule_id = '" . $schedule_id . "'");
//
//                    $crsmodel = DlStudentCourse::findOne($stdcrsid);
//                    $crsmodel->schedule_id = $schedule_id;
//                    $crsmodel->save();
//                }

                \Yii::$app->getSession()->setFlash('success', 'Schedule infos updated successfully!!!');
                return $this->redirect(['view', 'id' => $model->scr_id]);
            } else {
                //print_r($model->errors);exit;
            }
        }

        $instructors = ArrayHelper::map(
                        DlInstructors::find()->andWhere('admin_id = :adm_id', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'id', function($model, $defaultValue) {
                    return $model->first_name . " " . $model->last_name;
                });


        $available_instructors = ArrayHelper::map(DlInsAvailableDays::find()->andWhere('available_date = :available_date', [':available_date' => $model->schedule_date])->all(), 'instructor_id', 'instructor_id');

        if ($available_instructors)
            $instructors = array_intersect_key($instructors, $available_instructors);

        return $this->render('update', [
                    'model' => $model,
                    'instructors' => $instructors
        ]);
    }

    /**
     * Deletes an existing DbSchedules model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
//        DlStudentCourse::updateAll(['schedule_id' => 0], "schedule_id = '" . $id . "'");
//        $this->findModel($id)->delete();
//        $model = DbSchedules::find()->where(['schedule_id'=>$id])->delete();

        $this->findModel($id)->delete();
        \Yii::$app->getSession()->setFlash('success', 'Schedule infos deleted successfully!!!');

//        if(DbSchedules::find()->where(['schedule_id'=>$id])->delete()){       
//            \Yii::$app->getSession()->setFlash('success', 'Schedule infos deleted successfully!!!');
//        }else{
//            \Yii::$app->getSession()->setFlash('danger', 'Schedule infos not delete');
//        }
        return $this->redirect(['index']);
    }

    public function actionDeleteall($id) {
//        echo 'hi';exit;
//        $models = DbSchedules::find()->select('schedule_id')->Where(['scr_id' => $id])->all();       
//        $models->delete();
        DbSchedules::deleteAll(['scr_id' => $id]);
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
