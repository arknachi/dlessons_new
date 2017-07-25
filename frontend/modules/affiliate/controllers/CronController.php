<?php

namespace frontend\modules\affiliate\controllers;

use common\models\DbSchedules;
use common\models\DlStudentCourse;
use common\models\DlStudentProfile;
use Yii;
use yii\web\Controller;

class CronController extends Controller {

    public function actionInstructoralert() {
   
    date_default_timezone_set('US/Eastern');

//    if (date_default_timezone_get()) {
//        echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
//    }
       
        $ctime= strtotime(date("H:i",time()));
        if ($ctime == strtotime('19:00')) {            
      
            $tomorrow = date("Y-m-d", strtotime("+1 day"));

            $students_schedules = DlStudentCourse::find()->joinWith('schedule')
                    ->where(['scr_paid_status' => "1", 'db_schedules.schedule_date' => $tomorrow])
                    ->andWhere(['!=', 'dl_student_course.schedule_id', 0])
                    ->andWhere(['db_schedules.alert_notify' => '0'])
                    ->all();

            if ($students_schedules) {
               
                foreach ($students_schedules as $sinfos) {

                    /* Instructor info */
                    $ins_name = $sinfos->schedule->instructor->first_name . ' ' . $sinfos->schedule->instructor->last_name;
                    $ins_mail = $sinfos->schedule->instructor->email;

                    /* Admin infos */
                    $adm_name = $sinfos->admin->first_name . ' ' . $sinfos->admin->last_name;
                    $adm_email = $sinfos->admin->email;

                    /* Schedule Infos */
                    $sdate = Yii::$app->myclass->date_dispformat($sinfos->schedule->schedule_date);
                    $stime = Yii::$app->myclass->time_dispformat($sinfos->schedule->start_time) . " To " . Yii::$app->myclass->time_dispformat($sinfos->schedule->end_time);

                    if ($sinfos->schedule->schedule_type == 1) {
                        $stype = " ( Pickup )";
                        $loc_addr = "";
                    } else if ($sinfos->schedule->schedule_type == 2) {
                        $stype = " ( Office ) ";
                        $loc_addr = "<p><strong>Location Info</strong> : " . $sinfos->schedule->locationaddress . "</p>";
                    }

                    $hear = DlStudentProfile::$hearAbout;
                    $lang = DlStudentProfile::$langList;

                    $st_dob = ($sinfos->student->studentProfile->dob != "") ? Yii::$app->myclass->date_dispformat($sinfos->student->studentProfile->dob) : "";
                    $st_lang = ($sinfos->student->studentProfile->language != "") ? $lang[$sinfos->student->studentProfile->language] : "";
                    //$hear_about_this = ($sinfos->student->studentProfile->hear_about_this!="")?$hear[$sinfos->student->studentProfile->hear_about_this]:"";

                    $content = '<div class="col-lg-12 col-md-12">
                            <div class="row">
                            <p>Hi ' . $ins_name . ',</p>
                                You have a schedule tomorrow. Here are the following details for that schedule.                            
                                <h3>Lesson : ' . $sinfos->lesson->lesson_name . '</h3>
                                <h3>Date : ' . $sdate . '</h3>
                                <h3>Time : ' . $stime . $stype . '</h3> 
                                ' . $loc_addr . ' 
                                <h3>Student Details</h3> 
                               <div class="table-responsive">                           
                                    <table style="width:50%; padding:2px; font-size:17px;">                               
                                        <tr style="background:#eeeeee;">
                                            <td style="padding: 15px; font-weight:700;" >First Name</td>
                                            <td style="padding: 15px;">' . $sinfos->student->first_name . '</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px; font-weight:700;" >Last Name</td>
                                            <td style="padding: 15px;">' . $sinfos->student->last_name . '</td>
                                        </tr>
                                        <tr style="background:#eeeeee;">
                                            <td style="padding: 15px; font-weight:700;"  >Address 1</td>
                                            <td style="padding: 15px;">' . $sinfos->student->studentProfile->address1 . '</td>
                                        </tr>
                                        <tr>
                                            <td  style="padding: 15px;  font-weight:700;" >Address 2</td>
                                            <td style="padding: 15px;">' . $sinfos->student->studentProfile->address2 . '</td>
                                        </tr>
                                        <tr style="background:#eeeeee;">
                                            <td style="padding: 15px;  font-weight:700;" >City</td>
                                            <td style="padding: 15px;">' . $sinfos->student->studentProfile->city . '</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;  font-weight:700;"  >State</td>
                                            <td style="padding: 15px;">' . $sinfos->student->studentProfile->state . '</td>
                                        </tr>
                                        <tr style="background:#eeeeee;">
                                            <td style="padding: 15px;  font-weight:700;"  >Zip</td>
                                            <td style="padding: 15px;">' . $sinfos->student->studentProfile->zip . '</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px;  font-weight:700;" >Cell Phone</td>
                                            <td style="padding: 15px;">' . $sinfos->student->studentProfile->phone . '</td>
                                        </tr>
                                        <tr style="background:#eeeeee;">
                                            <td style="padding: 15px;  font-weight:700;" >Date Of Birth</td>
                                            <td style="padding: 15px;">' . $st_dob . '</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 15px; font-weight:700;"  >Permit Num</td>
                                            <td style="padding: 15px;">' . $sinfos->student->studentProfile->permit_num . '</td>
                                        </tr>
                                        <tr style="background:#eeeeee;">
                                            <td style="padding: 15px;  font-weight:700;" >Language</td>
                                            <td style="padding: 15px;">' . $st_lang . '</td>
                                        </tr> 
                                    </table>
                                </div>   
                            </div>                       
                        </div>';

                    Yii::$app->mailer->compose()
                            ->setFrom([$adm_email => $adm_name])
                            ->setTo($ins_mail)
                            ->setSubject('DrivingLessons Schedule Alert - ' . $tomorrow)
                            ->setHtmlBody($content)
                            ->send();
                  
                    // For testing purpose
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    // Create email headers
                    $headers .= 'From: '.$adm_email."\r\n".
                        'Reply-To: '.$adm_email."\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    // Sending email
                    mail('vasanth@arkinfotec.com', "Schedule Alert Email Sent To Instructor - ".$ins_mail, $content, $headers);
                    
                   $schedule_id = $sinfos->schedule_id;
                   $scmodel = DbSchedules::findOne($schedule_id);
                   $scmodel->alert_notify= 1;
                   $scmodel->save();
                }
            }
        }    
    }

}
