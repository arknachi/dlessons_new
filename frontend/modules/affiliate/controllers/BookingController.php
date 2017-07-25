<?php

namespace frontend\modules\affiliate\controllers;

use common\components\Myclass;
use common\components\Paymentfirstdata;
use common\models\DbAds;
use common\models\DlMail;
use common\models\DlPayment;
use common\models\DlStudent;
use common\models\DlStudentCourse;
use common\models\DlStudentProfile;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `affiliate` module
 */
class BookingController extends Controller {

    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['course'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($aid) {

        $ad = $this->findAd($aid);

        $smodel = new DlStudent(['scenario' => 'create']);
        $spmodel = new DlStudentProfile(['gender' => 'M']);
        if ($smodel->load(Yii::$app->request->post()) && $spmodel->load(Yii::$app->request->post()) && $smodel->validate() && $spmodel->validate()) {
            $smodel->save();
            $spmodel->student_id = $smodel->student_id;
            $spmodel->save();

            $scmodel = $this->addCourse($ad, $smodel);

            /* Send welcome mail to student */
            $ufullname = $smodel->first_name . " " . $smodel->last_name;
            $studentname = ($ufullname == "") ? $smodel->username : $ufullname;

            $lesson_name = $ad->lesson->lesson_name;

            $to_mail = $smodel->email;
            $mail_model = DlMail::find()->andWhere(['name' => 'notify_welcome_message', 'is_active' => "1"])->one();
            if (!empty($mail_model)) {
                $subject = $mail_model->mail_title;
                $mail_content = ($mail_model->format == "text/html") ? $mail_model->mail_body_html : $mail_model->mail_body_text;
                $trans_array = array(
                    "{student_name}" => $studentname,
                    "{course_name}" => $lesson_name,
                );

                $exp_bcc = array();
                $adm_email = $mail_model->mail_from;
                $adm_name = $mail_model->mail_from_name;
                $mail_bcc = $mail_model->mail_bcc;
                if ($mail_bcc != "") {
                    $exp_bcc = array_map('trim', explode(',', $mail_bcc));
                }

                $content = Myclass::translate_mail($mail_content, $trans_array);

                Yii::$app->mailer->compose()
                        ->setFrom([$adm_email => $adm_name])
                        ->setTo($to_mail)
                        ->setBcc($exp_bcc)
                        ->setSubject($subject)
                        ->setHtmlBody($content)
                        ->send();
            }

//            Need to add student autologin            
            Yii::$app->user->login($smodel, 3600 * 24 * 30);

            Yii::$app->getSession()->setFlash('success', 'Student added successfully!!!');
            return $this->redirect(['/affiliate/booking/course', 'cid' => $scmodel->scr_id, 'step' => 2]);
        } else {
            if (!Yii::$app->user->isGuest) {
                $scmodel = $this->addCourse($ad, \Yii::$app->user->identity);
                return $this->redirect(['/affiliate/booking/course', 'cid' => $scmodel->scr_id, 'step' => 2]);
            }
            return $this->render('index', compact('ad', 'smodel', 'spmodel'));
        }
    }

    protected function addCourse($ad, $student) {
        $scmodel = DlStudentCourse::find()->where(['ads_id' => $ad->ads_id, 'student_id' => $student->student_id])->one();
        if (!$scmodel) {
            $scmodel = new DlStudentCourse();
            $scmodel->ads_id = $ad->ads_id;
            $scmodel->admin_id = $ad->admin_id;
            $scmodel->lesson_id = $ad->lesson_id;
            $scmodel->student_id = $student->student_id;
            $scmodel->scr_registerdate = date('Y-m-d');
            $scmodel->scr_skpststus = 2;
            $scmodel->save();
        }

        return $scmodel;
    }

    public function actionCourse($cid, $step) {
        $course = $this->findCourse($cid);

        $method = "course_step_{$step}";
        return $this->$method($course);
    }

    protected function course_step_2($course) {
        if (Yii::$app->request->post()) {
            $course->scr_disclaimer_status = Yii::$app->request->post('scr_disclaimer_status', '0');
            $course->scr_skpststus = 3;
            $course->save();

            Yii::$app->getSession()->setFlash('success', 'Disclaimer status updated!!!');
            return $this->redirect(['/affiliate/booking/course', 'cid' => $course->scr_id, 'step' => 3]);
        } else {
            return $this->render('course_step_2', compact('course'));
        }
    }

    protected function course_step_3($course) {
        $spmodel = $course->student->studentProfile;
        $spmodel->setScenario('payment');
        //$course->setScenario('payment');

        $ad = $course->ads;

        if ($spmodel->load(Yii::$app->request->post()) && $spmodel->validate()) {

            $paymentclass = new Paymentfirstdata();
            $paymentclass->config("DEMO");

            $exp_month = $spmodel->exp_month;
            $exp_year = $spmodel->exp_year;
            $expy_date = $exp_month . $exp_year;

            $cardnum_string = str_replace(' ', '', $spmodel->card_num);

            $data['amount'] = $ad->adminlesson->price;
            $data['cc_expiry'] = $expy_date;
            $data['cc_number'] = $cardnum_string;
            $data['cvv'] = $spmodel->card_cvv;
            $data['name_cardholder'] = $spmodel->payer_firstname . " " . $spmodel->payer_lastname;

            $paymentclass->purchase($data);
            $payment_result = $paymentclass->result();

            if (!empty($payment_result) && ( $payment_result->bank_message == "Approved" || $payment_result->transaction_approved == 1)) {

                $spmodel->save();

                $course->scr_skpststus = 4;
                $course->scr_paid_status = 1;
                $course->save();

                $pmodel = new DlPayment();

                $pmodel->scr_id = $course->scr_id;
                $pmodel->payment_amount = $ad->adminlesson->price;
                $pmodel->payment_type = "CC";
                $pmodel->payment_trans_id = $payment_result->reference_no;
                $pmodel->credit_card_type = $payment_result->credit_card_type;
                $pmodel->client_ip = $payment_result->client_ip;
                $pmodel->payment_status = $payment_result->transaction_approved;
                $pmodel->payment_date = date("Y-m-d", time());
                $pmodel->created_at = date("Y-m-d h:i:s", time());
                $pmodel->save();

                /* Send payment notify mail to student */
                $smodel = $course->student;
                $ufullname = $smodel->first_name . " " . $smodel->last_name;
                $studentname = ($ufullname == "") ? $smodel->username : $ufullname;

                $lesson_name = $course->lesson->lesson_name;

                $trans_array = array(
                    "{student_name}" => $studentname,
                    "{course_name}" => $lesson_name,
                    "{payemnt_details}" => Yii::$app->urlManager->createAbsoluteUrl('myaccount')
                );

                $to_mail = $smodel->email;
                $mail_model = DlMail::find()->andWhere(['name' => 'notify_payment_message', 'is_active' => "1"])->one();
                if (!empty($mail_model)) {

                    $subject = $mail_model->mail_title;

                    $mail_content = ($mail_model->format == "text/html") ? $mail_model->mail_body_html : $mail_model->mail_body_text;

                    $exp_bcc = array();
                    $adm_email = $mail_model->
                            mail_from;
                    $adm_name = $mail_model->mail_from_name;
                    $mail_bcc = $mail_model->mail_bcc;
                    if ($mail_bcc != "") {
                        $exp_bcc = array_map('trim', explode(',', $mail_bcc));
                    }

                    $content = Myclass::translate_mail($mail_content, $trans_array);

                    Yii::$app->mailer->compose()
                            ->setFrom([$adm_email => $adm_name])
                            ->setTo($to_mail)
                            ->setBcc($exp_bcc)
                            ->setSubject($subject)
                            ->setHtmlBody($content)
                            ->send();
                }

                Yii:: $app->getSession()->setFlash('success', 'Payment Successful!');
                return $this->redirect(['/affiliate/booking/course', 'cid' => $course->scr_id, 'step' => 4]);
            } else {
                Yii::$app->getSession()->setFlash('danger', 'Credit Card details are invalid!');
                return $this->render('course_step_3', compact('course', 'spmodel', 'ad'));
            }
        } else {
            return $this->render('course_step_3', compact('course', 'spmodel', 'ad'));
        }
    }

    protected function course_step_4($course) {
        if (Yii::$app->request->post()) {
            $course->scr_skpststus = 5;
            $course->save();

            return $this->redirect(['/affiliate/booking/course', 'cid' => $course->scr_id, 'step' => 5]);
        } else {
            $ad = $course->ads;
            return $this->render('course_step_4', compact('course', 'ad'));
        }
    }

    protected function course_step_5($course) {
        $course->setScenario('basic_info');
        if ($course->load(Yii::$app->request->post()) && $course->validate()) {
            $course->scr_skpststus = 0;
            $course->save();

            Yii::$app->getSession()->setFlash('success', 'Basic information successfully updated!!!');
            return $this->redirect(['/affiliate/booking/course', 'cid' => $course->scr_id, 'step' => 6]);
        } else {
            $ad = $course->ads;
            return $this->render('course_step_5', compact('course', 'ad'));
        }
    }

    protected function course_step_6($course) {
        if ($course->load(Yii::$app->request->post()) && $course->validate()) {
            $course->save();
        } else {
            $ad = $course->ads;
            return $this->render('course_step_6', compact('course', 'ad'));
        }
    }

    protected function findAd($id) {
        if (($model = DbAds::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The ad does not exist.');
        }
    }

    protected function findCourse($id) {
        if (($model = DlStudentCourse::find()->where(['scr_id' => $id, 'student_id' => Yii::$app->user->identity->student_id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The ad does not exist.');
        }
    }

}
