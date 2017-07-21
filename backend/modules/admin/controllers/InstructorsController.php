<?php

namespace backend\modules\admin\controllers;

use common\components\Myclass;
use common\models\DlInsAvailableDays;
use common\models\DlInstructors;
use common\models\DlInstructorsSearch;
use Yii;
use yii2fullcalendar\models\Event as Event2;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * InstructorsController implements the CRUD actions for DlInstructors model.
 */
class InstructorsController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'delete', 'view', 'availabletimes', 'settimesins', 'fetchevents'],
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
     * Lists all DlInstructors models.
     * @return mixed
     */
    public function actionIndex() {
        
        $searchModel = new DlInstructorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DlInstructors model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionSettimesins() {
        $events = array();
        if (Yii::$app->request->isAjax) {
            $ins_id = Yii::$app->request->post('cins_id');
            $available_days = Yii::$app->request->post('available_day');
            $start_time = Yii::$app->request->post('start_time');
            $end_time = Yii::$app->request->post('end_time');


            foreach ($available_days as $key => $day) {
                $stime = $start_time[$key];
                $etime = $end_time[$key];

                if ($stime != "")
                    $astime = date('H:i:s', strtotime($stime));

                if ($etime != "")
                    $aetime = date('H:i:s', strtotime($etime));

                $chck_avmodel = DlInsAvailableDays::find()->where([
                            'available_date' => $day,
                            'instructor_id' => $ins_id,
                        ])->one();

                if ($chck_avmodel) {
                    if ($stime != "" && $aetime != "") {
                        $chck_avmodel->instructor_id = $ins_id;
                        $chck_avmodel->available_date = $day;
                        $chck_avmodel->start_time = $astime;
                        $chck_avmodel->end_time = $aetime;
                        $chck_avmodel->save();
                    } else {
                        $chck_avmodel->delete();
                    }
                } else {
                    if ($stime != "" && $aetime != "") {
                        $add_aval = new DlInsAvailableDays();
                        $add_aval->instructor_id = $ins_id;
                        $add_aval->available_date = $day;
                        $add_aval->start_time = $astime;
                        $add_aval->end_time = $aetime;
                        $add_aval->save();
                    }
                }
            }

            /* Ins Free Times */
            $ins_schedules = DlInsAvailableDays::find()->where(['instructor_id' => $ins_id])->all();
            $events = array();
            foreach ($ins_schedules AS $sinfo) {
                $stime = date('h:i a', strtotime($sinfo->start_time));
                $etime = date('h:i a', strtotime($sinfo->end_time));
                $Event = new Event2();
                $Event->id = $sinfo->available_id;
                $Event->title = $stime." - ".$etime;
                $Event->start = $sinfo->available_date . ' ' . $sinfo->start_time;
                $Event->end = $sinfo->available_date . ' ' . $sinfo->end_time;
                $Event->color = '#00A65A';
                $events[] = $Event;
            }
            return Json::encode($events);
        }
    }

    public function actionAvailabletimes() {

        if (Yii::$app->request->isAjax) {

            $data = array();
            $data['exst_avmodel'] = array();

            $data['schedule_date'] = Yii::$app->request->post('schedule_date');
            $data['ins_id'] = Yii::$app->request->post('ins_id');

            $chck_avmodel = ArrayHelper::map(DlInsAvailableDays::find()->where([
                                'instructor_id' => Yii::$app->request->post('ins_id'),
                            ])->all(), "available_date", function($model, $defaultValue) {
                        return $model->start_time . "~" . $model->end_time;
                    });
            if (!empty($chck_avmodel))
                $data['exst_avmodel'] = $chck_avmodel;

            echo $this->renderPartial('@backend/modules/admin/views/instructors/_availabletimes', $data, false, true);
            exit;
        }
    }

    /**
     * Creates a new DlInstructors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $session = Yii::$app->session;
        $model = new DlInstructors();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->admin_id = Yii::$app->user->identity->ParentAdminId;
            $model->created_at = date("Y-m-d H:i:s");
            $model->updated_at = date("Y-m-d H:i:s");
            if ($session->has('cityid')) {      
                $model->city_id = $session->get('cityid');
            }
            $model->save();

            \Yii::$app->getSession()->setFlash('success', 'Instructor infos added successfully!!!');
            return $this->redirect(['index']);
        } else {
            if (!$model->status)
                $model->status = 1;

            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DlInstructors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        /* Ins Free Times */
        $ins_schedules = DlInsAvailableDays::find()->where(['instructor_id' => $id])->all();
        $events = array();
        foreach ($ins_schedules AS $sinfo) {
            $stime = date('h:i a', strtotime($sinfo->start_time));
            $etime = date('h:i a', strtotime($sinfo->end_time));
            $Event = new Event2();
            $Event->id = $sinfo->available_id;
            $Event->title = $stime." - ".$etime;
            $Event->start = $sinfo->available_date . ' ' . $sinfo->start_time;
            $Event->end = $sinfo->available_date . ' ' . $sinfo->end_time;
            $Event->color = '#00A65A';
            $events[] = $Event;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->updated_at = date("Y-m-d H:i:s");
            if (!$model->password) {           
                unset($model->password);
            }
            $model->save();

            \Yii::$app->getSession()->setFlash('success', 'Instructor infos updated successfully!!!');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'events' => $events
            ]);
        }
    }

    public function actionFetchevents() {
        
    }

    /**
     * Deletes an existing DlInstructors model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
