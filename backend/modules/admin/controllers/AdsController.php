<?php

namespace backend\modules\admin\controllers;

use common\models\DbAds;
use common\models\DbAdsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\BaseFileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AdsController implements the CRUD actions for DbAds model.
 */
class AdsController extends Controller {

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
     * Lists all DbAds models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DbAdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DbAds model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DbAds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $model = new DbAds();

        if ($model->load(Yii::$app->request->post())) {

            $image_path = Yii::getAlias('@root_files_path') . '/frontend/web/uploads/ads/';
            if (!is_dir($image_path)) {
                BaseFileHelper::createDirectory($image_path, 0777, true);
            }

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                $image = $model->imageFile;
                $ext = end((explode(".", $image->name)));

                // store the source file name
                $model->file_name = $image->name;

                // generate a unique file name
                $image_nme = Yii::$app->security->generateRandomString() . ".{$ext}";

                // the path to save file, you can set an uploadPath
                $path = $image_path . $image_nme;

                $model->image = 'uploads/ads/' . $image_nme;

                $model->admin_id = Yii::$app->user->identity->ParentAdminId;
                $model->created_by = Yii::$app->user->getId();

                if ($model->save()) {
                    $image->saveAs($path);
                    \Yii::$app->getSession()->setFlash('success', 'Ads created successfully for the course!!!');
                    return $this->redirect(['view', 'id' => $model->ads_id]);
                }
            }
        }


        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing DbAds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionUpdate($id) {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->ads_id]);
//        } else {
//            return $this->render('update', [
//                        'model' => $model,
//            ]);
//        }
//    }

    /**
     * Deletes an existing DbAds model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DbAds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DbAds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DbAds::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
