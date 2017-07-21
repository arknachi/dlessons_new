<?php

namespace backend\modules\admin\controllers;

use common\models\DlAdminLessons;
use common\models\DlLessons;
use common\models\DlLessonsSearch;
use common\models\DlStudentCourse;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * LessonsController implements the CRUD actions for DlLessons model.
 */
class LessonsController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'delete'],
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
     * Lists all DlLessons models.
     * @return mixed
     */
    public function actionIndex() {
        //$searchModel = new DlLessonsSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $adminid = Yii::$app->user->identity->ParentAdminId;
        $searchModel = new DlLessonsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $adminid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new DlLessons();
        $almodel = new DlAdminLessons();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->isAjax && $almodel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($almodel);
        }

        $model->admin_id = Yii::$app->user->identity->ParentAdminId;
        $model->isDeleted = 0;
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $almodel->load(Yii::$app->request->post()) && $almodel->validate()) {

            $model->save();

            $almodel->admin_id = $model->admin_id;
            $almodel->lesson_id = $model->lesson_id;
            $almodel->save();

            \Yii::$app->getSession()->setFlash('success', 'Lesson Infos added successfully!!!');
            return $this->redirect(['index']);
        } else {

            if (!$almodel->status)
                $almodel->status = 1;

            return $this->render('create', [
                        'model' => $model,
                        'almodel' => $almodel
            ]);
        }
    }

    /**
     * Updates an existing DlLessons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $admin_id = Yii::$app->user->identity->ParentAdminId;
        $model = $this->findModel($id);
        $almodel = DlAdminLessons::find()->where(['lesson_id' => $id])->one();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->isAjax && $almodel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($almodel);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $almodel->load(Yii::$app->request->post()) && $almodel->validate()) {

            $model->save();
            $almodel->save();

            \Yii::$app->getSession()->setFlash('success', 'Lesson Infos updated successfully!!!');
            return $this->redirect(['index']);
        } else {

            return $this->render('update', [
                        'model' => $model,
                        'almodel' => $almodel
            ]);
        }
    }

    /**
     * Deletes an existing DlLessons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {        
       
        $schedule_exist = DlStudentCourse::find()->where(['lesson_id' => $id])->count(); 
        if($schedule_exist>0)
        {
            \Yii::$app->getSession()->setFlash('danger', 'You can not delete this lesson. Some of the schedules are assigned to this lesson!!!');           
        }else{     
            \Yii::$app->getSession()->setFlash('success', 'Lesson deleted successfully!!!');
            $this->findModel($id)->delete();
            $almodel = DlAdminLessons::find()->where(['lesson_id' => $id])->one();
            $almodel->status = 0;
            $almodel->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the DlLessons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DlLessons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DlLessons::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
