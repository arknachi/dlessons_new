<?php

namespace backend\modules\suadmin\controllers;

use common\models\DlAdmin;
use common\models\DlAdminCities;
use common\models\DlAdminLessons;
use common\models\DlAdminSearch;
use common\models\DlCity;
use stdClass;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\BaseFileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * AdminController implements the CRUD actions for DlAdmin model.
 */
class AdminController extends Controller {

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
                        'actions' => ['logout', 'index', 'create', 'update', 'delete', 'ajaxcreate'],
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

    public function actionAjaxcreate() {
        if (Yii::$app->request->isAjax) {
            $model = new DlAdmin();
            $lmodel = new DlAdminLessons();
            $lmodel->ctitle = $_POST['label'];
           

            $result = new stdClass();
            $result->cid = $_POST['id'];

            $result->html = $this->renderPartial('_formWcourse', array('cid' => $_POST['id'], 'lmodel' => $lmodel, 'model' => $model), TRUE);

            echo function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);

            Yii::$app->end();
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all DlAdmin models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = new DlAdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DlAdmin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DlAdmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new DlAdmin();
        $model->scenario = 'createadmin';
        $cmodel = new DlAdminCities();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        $logo_path = Yii::getAlias('@root_files_path') . '/frontend/web/uploads/logos/';
        if (!is_dir($logo_path)) {
            BaseFileHelper::createDirectory($logo_path, 0777, true);
        }
        
        if(Yii::$app->request->post())
        {
            $model->load(Yii::$app->request->post());
        }    

        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        
        if (Yii::$app->request->post() && $model->validate()) {
       
            if($model->imageFile)
            {    
                $image = $model->imageFile;
            
                $ext = end((explode(".", $image->name)));

                // store the source file name
                //$model->file_name = $image->name;

                // generate a unique file name
                $image_nme = Yii::$app->security->generateRandomString() . ".{$ext}";

                // the path to save file, you can set an uploadPath
                $path = $logo_path . $image_nme;

                $model->logo = $image_nme;               
                
            }    

            $model->created_at = date("Y-m-d H:i:s");
            $model->updated_at = date("Y-m-d H:i:s");
            $model->save();
            
            if($model->imageFile)
            { 
               $image->saveAs($path); 
            }

         //   $admin = DlAdmin::find()->where(['admin_id' => $model->admin_id])->one();

//            if (isset($_POST['DlAdminLessons'])) {
//                $courses = isset($_POST['DlAdminLessons']['courses']) ? $_POST['DlAdminLessons']['courses'] : "";
//                if ($courses != "") {
//                    foreach ($courses as $k => $values) {
//                        $lessons = DlLessons::find()->where(['lesson_id' => $k])->one();
//                        $extraColumns = ['price' => $values['price'], 'status' => 1,'created_at'=> date("Y-m-d H:i:s",time())]; // extra columns to be saved to the many to many table
//                        $unlink = true; // unlink tags not in the list
//                        $delete = true; // delete unlinked tags
//                        $admin->link('lessons', $lessons, $extraColumns, $unlink, $delete);
//                    }
//                }
//            }
            
             /* Cities added to the client */            
            $admin = DlAdmin::find()->where(['admin_id' => $model->admin_id])->one();       
            if (isset($_POST['DlAdminCities'])) {
                $citylist = isset($_POST['DlAdminCities']['citylist']) ? $_POST['DlAdminCities']['citylist'] : "";
                if ($citylist != "") {                   
                    foreach ($citylist as $values) {
                        $cities = DlCity::find()->where(['city_id' => $values])->one();                       
                        $admin->link('cities', $cities);                     
                    }
                }
            }

            \Yii::$app->getSession()->setFlash('success', 'Client infos added successfully!!!');
            return $this->redirect(['index']);
        } else {
            $model->status = 1;
            return $this->render('create', [
                        'model' => $model,
                   //     'lmodel' => $lmodel
                        'cmodel' => $cmodel
            ]);
        }
    }

    /**
     * Updates an existing DlAdmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
       // $selected_lessonids = array();
       // $lesson_infos = array();
        $selected_cityids = array();
        $model = $this->findModel($id);
       // $lmodel = new DlAdminLessons();
        $cmodel = new DlAdminCities();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

//        /* Existing Assigned lessons */
//        $admin_lessons = DlAdminLessons::find()->andWhere(['admin_id' => $id])->all();
//        if (!empty($admin_lessons)) {
//            foreach ($admin_lessons as $linfo) {
//                $selected_lessonids[] = $linfo->lesson_id;
//                $lesson_infos[$linfo->lesson_id]['lesson_name'] = $linfo->lessons->lesson_name;
//                $lesson_infos[$linfo->lesson_id]['price'] = $linfo->price;
//            }
//        }
        
         /* Existing Assigned Cities */
        $admin_cities = DlAdminCities::find()->andWhere(['admin_id' => $id])->all();
        if (!empty($admin_cities)) {
            foreach ($admin_cities as $cinfo) {
                $selected_cityids[] = $cinfo->city_id;                            
            }
        }

        $update_admin = $model->load(Yii::$app->request->post());

        if($model->password=="")
        unset($model->password);
        
        $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        
        if ($update_admin && $model->validate()) {
            
            if($model->imageFile)
            {    
                $logo_path = Yii::getAlias('@root_files_path') . '/frontend/web/uploads/logos/';
                $image = $model->imageFile;            
                $ext = end((explode(".", $image->name)));
                // generate a unique file name
                $image_nme = Yii::$app->security->generateRandomString() . ".{$ext}";
                // the path to save file, you can set an uploadPath
                $path = $logo_path . $image_nme;
                $model->logo = $image_nme;  
            }    

            $model->updated_at = date("Y-m-d H:i:s");
            $model->save();
            if($model->imageFile)
            { 
               $image->saveAs($path); 
            }

//            $admin = DlAdmin::find()->where(['admin_id' => $model->admin_id])->one();
//            $admin->unlinkAll('lessons', true);
//            if (isset($_POST['DlAdminLessons'])) {
//                $courses = isset($_POST['DlAdminLessons']['courses']) ? $_POST['DlAdminLessons']['courses'] : "";
//                if ($courses != "") {
//                    $unlink = true; // unlink tags not in the list
//                    $delete = true; // delete unlinked tags
//                    foreach ($courses as $k => $values) {
//                        $lessons = DlLessons::find()->where(['lesson_id' => $k])->one();
//                        $extraColumns = ['price' => $values['price'], 'status' => 1,'created_at'=> date("Y-m-d H:i:s",time()),'updated_at'=> date("Y-m-d H:i:s",time())]; // extra columns to be saved to the many to many table
//                        $admin->link('lessons', $lessons, $extraColumns, $unlink, $delete);
//                        // $admin->linkAll('lessons', $lessons, $extraColumns, $unlink, $delete);
//                    }
//                }
//            }
            
            /* Cities updated to the client */            
            $admin = DlAdmin::find()->where(['admin_id' => $model->admin_id])->one();
            $admin->unlinkAll('cities', true);
            if (isset($_POST['DlAdminCities'])) {
                $citylist = isset($_POST['DlAdminCities']['citylist']) ? $_POST['DlAdminCities']['citylist'] : "";
                if ($citylist != "") {                   
                    foreach ($citylist as $values) {
                        $cities = DlCity::find()->where(['city_id' => $values])->one();                       
                        $admin->link('cities', $cities);                     
                    }
                }
            }

            \Yii::$app->getSession()->setFlash('success', 'Client infos updated successfully!!!');
            return $this->redirect(['index']);
        } else {

            return $this->render('update', [
                        'model' => $model,
                       // 'lmodel' => $lmodel,
                          'cmodel' => $cmodel,
                          'selected_cityids' => $selected_cityids,
                       // 'selected_lessonids' => $selected_lessonids,
                       // 'lesson_infos' => $lesson_infos
            ]);
        }
    }

    /**
     * Deletes an existing DlAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
