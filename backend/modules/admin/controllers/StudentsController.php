<?php

namespace backend\modules\admin\controllers;

use common\components\Myclass;
use common\models\DlStudent;
use common\models\DlStudentProfile;
use common\models\DlStudentSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * StudentsController implements the CRUD actions for DlStudent model.
 */
class StudentsController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'delete', 'view'],
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
     * Lists all DlStudent models.
     * @return mixed
     */
    public function actionIndex() {
        
        $searchModel = new DlStudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DlStudent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $studentModel = $this->findModel($id);

        return $this->render('view', [
                    'model' => $studentModel
        ]);
    }

    /**
     * Creates a new DlStudent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new DlStudent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->student_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DlStudent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $profilemodel = DlStudentProfile::findOne(["student_id" => $id]);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $profilemodel->load(Yii::$app->request->post());
            if ($model->validate() && $profilemodel->validate()) { 
                $model->save();
                $profilemodel->save();
                \Yii::$app->getSession()->setFlash('success', 'Profile Updated successfully!!!');
                return $this->redirect(['index']);
            }
        }
        
        $model->password = Myclass::refdecryption($model->password);
       
        return $this->render('update', [
                    'model' => $model,
                    'profilemodel' => $profilemodel
        ]);
    }

    /**
     * Deletes an existing DlStudent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DlStudent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DlStudent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DlStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
