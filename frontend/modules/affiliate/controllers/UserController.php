<?php

namespace frontend\modules\affiliate\controllers;

use common\components\Myclass;
use common\models\DlStudent;
use common\models\DlStudentProfile;
use common\models\StudentLoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `affiliate` module
 */
class UserController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['autologin'],
                        'allow' => true,                       
                    ],
                    [
                        'actions' => ['index','update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }
    
    public function actionAutologin($uid){
        
        if (!Yii::$app->user->isGuest) {           
           Yii::$app->user->logout();
        }
        
        if(is_numeric($uid)){
            $studentinf = DlStudent::findOne($uid);            
            if($studentinf){                 
                $model = new StudentLoginForm();
                $model->username = $studentinf->username;
                $model->password = Myclass::refdecryption($studentinf->password);
                if($model->login()){
                     return $this->redirect(['/affiliate/user/index']);
                }
            }else{
                Yii::$app->user->logout();               
                return $this->redirect(['/affiliate/user/index']);
            }
        }else{
            Yii::$app->user->logout();               
            return $this->redirect(['/affiliate/user/index']);
        }
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $student = $this->findStudent(Yii::$app->user->identity->student_id);        
        $facebook_url =  $student->getDlStudentCourses()->one()->admin->facebook_url; 
        
        return $this->render('index', compact('student','facebook_url'));
    }

    public function actionUpdate() {
        $id = Yii::$app->user->identity->student_id;
        
        $model = $this->findStudent(Yii::$app->user->identity->student_id);
        $profilemodel = DlStudentProfile::findOne(["student_id" => $id]);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $profilemodel->load(Yii::$app->request->post());
            if ($model->validate() && $profilemodel->validate()) {
                unset($model->password);
                $model->save();
                $profilemodel->dob = (isset($profilemodel->dob)) ? Yii::$app->formatter->asDate($profilemodel->dob, 'php:Y-m-d') : "";
                $profilemodel->save();
                \Yii::$app->getSession()->setFlash('success', 'Profile Updated successfully!!!');
                return $this->redirect(['index']);
            }
        }
        return $this->render('profile', [
                    'model' => $model,
                    'profilemodel' => $profilemodel
        ]);
    }

    protected function findStudent($id) {      
        if (($model = DlStudent::findOne($id)) !== null) {           
            return $model;
        } else {
            throw new NotFoundHttpException('The student does not exist.');
        }
    }

}
