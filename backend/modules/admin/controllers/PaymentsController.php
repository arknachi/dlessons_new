<?php

namespace backend\modules\admin\controllers;

use common\models\DlAdmin;
use common\models\DlPayment;
use common\models\DlStudentCourse;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PaymentsController implements the CRUD actions for DlPayment model.
 */
class PaymentsController extends Controller {

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
     * Lists all DlPayment models.
     * @return mixed
     */
    public function actionIndex() {
        //$searchModel = new DlPaymentSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $adminid = Yii::$app->user->identity->ParentAdminId;
        $admin_model = DlAdmin::find()->where(['admin_id' => $adminid])->one();
        $students_list = $admin_model->getMyPayments();

        $dataProvider = new ActiveDataProvider([
            'query' => $students_list,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        return $this->render('index', [
                    // 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DlPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DlPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new DlPayment();

        if ($model->load(Yii::$app->request->post())) {

            $model->payment_date = date("Y-m-d", strtotime($model->payment_date));
            $model->created_at = date("Y-m-d H:i:s", time());
            if ($model->validate()) {

                \Yii::$app->getSession()->setFlash('success', 'Payment infos added successfully!!!');
                $model->save();

                $scmodel = DlStudentCourse::findOne($model->scr_id);   
               
                if ($model->payment_status) {
                    $scmodel->scr_paid_status = 1;
                } else {
                    $scmodel->scr_paid_status = 2;
                }
                $scmodel->save(false);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DlPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $model->payment_date = date("Y-m-d", strtotime($model->payment_date));
            $model->save();

            $scmodel = DlStudentCourse::findOne($model->scr_id);     
           
            if ($model->payment_status) {
                $scmodel->scr_paid_status = 1;
            } else {
                $scmodel->scr_paid_status = 2;
            }
            
            $scmodel->save(false);

            \Yii::$app->getSession()->setFlash('success', 'Payment updated successfully!!!');
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DlPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DlPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DlPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DlPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
