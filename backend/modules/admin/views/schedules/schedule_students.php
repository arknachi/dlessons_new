<?php

use common\models\DlStudentProfile;
use yii\widgets\DetailView;

$this->title = 'Student Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <h3><?php echo "Date: ".Yii::$app->myclass->date_dispformat($model->schedule_date);  ?></h3>
        <h3><?php echo "Time: ".Yii::$app->myclass->time_dispformat($model->start_time) . " To " . Yii::$app->myclass->time_dispformat($model->end_time);
                if($model->schedule_type==1){
                        echo " ( Pickup )";
                    }else if($model->schedule_type==2){
                        echo " ( Office ) ";
                    }?>
        </h3> 
        <?php if($model->schedule_type==2){ ?>
        <p><strong>Location Info</strong> : <?php echo $model->locationaddress;?></p>
        <?php } 
        
         if($model->scr_completed_status==0){ 
             echo "<h3>Schedule Status : Pending</h3>";
         }
         
        if($model->scr_completed_status==1){ 
            echo "<h3>Schedule Status : Completed </h3>";
            echo "<h3>Course Completed Date : ". Yii::$app->myclass->date_dispformat($scrsmodel->scr_completed_date)."</h3>";
        } 
        
        $hear = DlStudentProfile::$hearAbout;
        $lang = DlStudentProfile::$langList;
       
        echo DetailView::widget([
                    'model' => $scrsmodel,
                    'attributes' => [           
                       'student.first_name',
                        'student.last_name',
                        'student.studentProfile.address1',
                        'student.studentProfile.address2',
                        'student.studentProfile.city',
                        'student.studentProfile.state',
                        'student.studentProfile.zip',
                        'student.studentProfile.phone',                       
                        [
                            'label' => 'Date Of Birth',
                            'format' => 'raw',
                            'value' => ($scrsmodel->student->studentProfile->dob!="")? Yii::$app->myclass->date_dispformat($scrsmodel->student->studentProfile->dob):"",
                        ],
                        'student.studentProfile.permit_num',
                        [
                            'label' => 'Language',
                            'format' => 'raw',
                            'value' => ($scrsmodel->student->studentProfile->language!="")?$lang[$scrsmodel->student->studentProfile->language]:"",
                        ],
                        [
                            'label' => 'hear about this',
                            'format' => 'raw',
                            'value' => ($scrsmodel->student->studentProfile->hear_about_this!="")?$hear[$scrsmodel->student->studentProfile->hear_about_this]:"",
                        ],                      
                        'student.studentProfile.referred_by', 
                    ],
                ]) ?>      
    </div>
</div>