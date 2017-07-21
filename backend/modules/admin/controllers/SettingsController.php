<?php

namespace backend\modules\admin\controllers;

use Yii;
use common\models\DlSettings;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingsController implements the CRUD actions for DlSettings model.
 */
class SettingsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [                   
                ],
            ],
        ];
    }

    /**
     * Lists all DlSettings models.
     * @return mixed
     */
    public function actionIndex()
    {
       $dashboard_boxes = ['Lessons'=>'Lessons','Instructors'=>'Instructors','Schedules'=>'Schedules','Students'=>"Students",'Payments'=>"Payments"]; 
       $model = new DlSettings();       
       $setting_attr = $model->attributeLabels(); 
        
       if ($model->load(Yii::$app->request->post())) {  
           
           foreach($setting_attr as $akey => $avalue){
            $smodel = DlSettings::find()->where(["option_type"=>$akey,"admin_id"=>Yii::$app->user->identity->ParentAdminId])->one();   
                if(isset($smodel) && !empty($smodel)){
                    $optionval = json_encode($model->dashboard);
                    $smodel->option_value = $optionval;
                    $smodel->save();
                }else{                    
                    $smodel = new DlSettings();   
                    $optionval = json_encode($model->dashboard);  
                    
                    if($akey=="dashboard")
                    $smodel->option_name="Dashboard Displays";
                    
                    $smodel->option_value = $optionval;
                    $smodel->option_type = $akey;                    
                    $smodel->admin_id = Yii::$app->user->identity->ParentAdminId;
                    $smodel->save();
                }
           }
           
           \Yii::$app->getSession()->setFlash('success', 'Settings updated successfully!!!');
           return $this->redirect(['index']);
        }
        
        if(isset($setting_attr) && !empty($setting_attr)){
            foreach($setting_attr as $akey => $avalue){
                $optionval_obj =  DlSettings::find()->where(["option_type"=>$akey,"admin_id"=>Yii::$app->user->identity->ParentAdminId])->one();
                if(isset($optionval_obj) && !empty($optionval_obj)){
                    $optionval = json_decode($optionval_obj->option_value, true);                  
                    $model->$akey = $optionval;    
                }
            }
        }
        
        return $this->render('index', [
            'model' => $model,
            'dashboard_boxes' => $dashboard_boxes,
        ]);
    }

}
