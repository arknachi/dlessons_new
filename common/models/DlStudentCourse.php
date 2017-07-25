<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dl_student_course".
 *
 * @property integer $scr_id
 * @property integer $ads_id
 * @property integer $lesson_id
 * @property integer $admin_id
 * @property integer $student_id
 * @property integer $schedule_id
 * @property integer $scr_disclaimer_status
 * @property integer $scr_paid_status
 * @property integer $scr_skpststus
 * @property string $scr_skip_url
 * @property string $scr_registerdate
 * @property string $scr_completed_date
 * @property integer $scr_completed_status
 * @property string $scr_certificate_serialno
 * @property integer $scr_certificate_status
 * @property string $scr_certificate_send_date
 * @property integer $scr_status
 * @property string $preferred_days
 * @property string $preferred_time
 * @property string $additional_infos
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DlPayment[] $dlPayments
 * @property DlAdmin $admin
 * @property DlLessons $lesson
 * @property DbAds $ads
 * @property DlStudent $student
 */
class DlStudentCourse extends ActiveRecord {

    public static $preferDays = ['1' => 'First available', '2' => 'Anytime is good', '3' => 'Only weekdays', '4' => 'Only Weekends'];
    public static $preferTime = ['1' => 'Anytime', '2' => 'Morning', '3' => 'Afternoon', '4' => 'Evening'];
    public $saddress1;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_student_course';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ads_id', 'lesson_id', 'admin_id', 'student_id', 'scr_registerdate'], 'required'],
            [['preferred_days', 'preferred_time'], 'required', 'on' => 'basic_info'],
            [['ads_id', 'lesson_id', 'admin_id', 'student_id', 'schedule_id', 'scr_disclaimer_status', 'scr_paid_status', 'scr_skpststus', 'scr_completed_status', 'scr_certificate_status', 'scr_status'], 'integer'],
            [['scr_registerdate', 'scr_completed_date', 'scr_certificate_send_date', 'created_at', 'updated_at'], 'safe'],
            [['additional_infos'], 'string'],
            [['scr_skip_url', 'scr_certificate_serialno', 'preferred_days', 'preferred_time'], 'string', 'max' => 255],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => DlAdmin::className(), 'targetAttribute' => ['admin_id' => 'admin_id']],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => DlLessons::className(), 'targetAttribute' => ['lesson_id' => 'lesson_id']],
            [['ads_id'], 'exist', 'skipOnError' => true, 'targetClass' => DbAds::className(), 'targetAttribute' => ['ads_id' => 'ads_id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => DlStudent::className(), 'targetAttribute' => ['student_id' => 'student_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'scr_id' => 'Scr ID',
            'ads_id' => 'Ads',
            'lesson_id' => 'Lesson',
            'admin_id' => 'Admin',
            'student_id' => 'Student',
            'schedule_id' => 'Schedule',
            'scr_disclaimer_status' => 'Disclaimer Status',
            'scr_paid_status' => 'Paid Status',
            'scr_skpststus' => 'Scr Skpststus',
            'scr_skip_url' => 'Scr Skip Url',
            'scr_registerdate' => 'Registration Date',
            'scr_completed_date' => 'Completed Date',
            'scr_completed_status' => 'Status',
            'scr_certificate_serialno' => 'Certificate Serialno',
            'scr_certificate_status' => 'Certificate Status',
            'scr_certificate_send_date' => 'Certificate Send Date',
            'scr_status' => 'Scr Status',
            'preferred_days' => 'Preferred Days',
            'preferred_time' => 'Preferred Time',
            'additional_infos' => 'Additional details',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function student_courses($lesson_id, $adminid, $schedule_id = 0) {
        $query = DlStudentCourse::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'lesson_id' => $lesson_id,
            'admin_id' => $adminid,
            'schedule_id' => $schedule_id,
            'scr_paid_status' => 1
        ]);

        return $dataProvider;
    }

    /**
     * @return ActiveQuery
     */
    public function getPayment() {
        return $this->hasMany(DlPayment::className(), ['scr_id' => 'scr_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSchedule() {
        return $this->hasOne(DbSchedules::className(), ['schedule_id' => 'schedule_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAdmin() {
        return $this->hasOne(DlAdmin::className(), ['admin_id' => 'admin_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLesson() {
        return $this->hasOne(DlLessons::className(), ['lesson_id' => 'lesson_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAds() {
        return $this->hasOne(DbAds::className(), ['ads_id' => 'ads_id']);
    }

    public function getScheduleinfo() {
        $sphone = "";
        if ($this->schedule->schedule_type == 1) {
            $type = " <span class='label label-success'>Pickup</span>";
            $address = $this->getStudentaddress();

            $sphone = "<br><strong>Phone :</strong> ";
            if ($this->student->studentProfile->phone != "") {
                $sphone .= $this->student->studentProfile->phone;
            } else {
                $sphone .= "Not Available";
            }
        } else if ($this->schedule->schedule_type == 2) {
            $type = " <span class='label label-success'>Office</span>";
            $address = $this->schedule->locationaddress;
        }
        $sinfo = "<p><strong>Student Name :</strong> " . $this->student->first_name . " " . $this->student->last_name;
        $sinfo .= "<br><strong>Instructor :</strong> " . $this->schedule->instructor->first_name . " " . $this->schedule->instructor->last_name;
        $sinfo .= "<br><strong>Type :</strong> " . $type;
        $sinfo .= "<br><strong>Address :</strong> " . $address . $sphone . " </p>";

        return $sinfo;
    }

    public function getInstructorname() {
        $sinfo = $this->schedule->instructor->first_name . " " . $this->schedule->instructor->last_name;
        return $sinfo;
    }

    public function getStudentname() {
        $sinfo = $this->student->first_name . " " . $this->student->last_name;
        return $sinfo;
    }

    public function getStudentinfo() {

        $sinfo = $this->student->first_name . ", " . $this->student->last_name;

        if ($this->student->studentProfile->address1 != "") {
            $sinfo .= ", " . $this->student->studentProfile->address1;
        }

        if ($this->student->studentProfile->address2 != "") {
            $sinfo .= ", " . $this->student->studentProfile->address2;
        }

        if ($this->student->studentProfile->city != "") {
            $sinfo .= ", " . $this->student->studentProfile->city;
        }

        if ($this->student->studentProfile->state != "") {
            $sinfo .= ", " . $this->student->studentProfile->state;
        }

        if ($this->student->studentProfile->zip != "") {
            $sinfo .= ", " . $this->student->studentProfile->zip;
        }

        if ($this->student->studentProfile->phone != "") {
            $sinfo .= " ( " . $this->student->studentProfile->phone . " )";
        }

        return $sinfo;
    }

    public function getStudentaddress() {

        $sinfo = "";

        if ($this->student->studentProfile->address1 != "") {
            $sinfo .= $this->student->studentProfile->address1;
        }

        if ($this->student->studentProfile->address2 != "") {
            $sinfo .= ", " . $this->student->studentProfile->address2;
        }

        if ($this->student->studentProfile->city != "") {
            $sinfo .= ", " . $this->student->studentProfile->city;
        }

        if ($this->student->studentProfile->state != "") {
            $sinfo .= ", " . $this->student->studentProfile->state;
        }

        if ($this->student->studentProfile->zip != "") {
            $sinfo .= ", " . $this->student->studentProfile->zip;
        }

        return $sinfo;
    }

    /**
     * @return ActiveQuery
     */
    public function getStudent() {
        return $this->hasOne(DlStudent::className(), ['student_id' => 'student_id']);
    }

    public static function find() {
        $query = parent::find();

        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['admin_id' => $adminid]);
        }
        return $query;
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created_at = date("Y-m-d h:i:s", time());
        }

        return parent::beforeSave($insert);
    }

}
