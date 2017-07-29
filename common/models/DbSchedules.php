<?php

namespace common\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "db_schedules".
 *
 * @property integer $schedule_id
 * @property integer $lesson_id
 * @property integer $instructor_id
 * @property string $schedule_date
 * @property string $start_time
 * @property string $end_time
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class DbSchedules extends ActiveRecord {

    public $stdcrsid,$total_hours,$remaining_hours;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'db_schedules';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['lesson_id', 'instructor_id', 'schedule_date'], 'required'],
            [['stdcrsid'], 'required', 'on' => 'create'],
            [['lesson_id', 'instructor_id', 'created_by', 'updated_by'], 'integer'],
            [['schedule_date', 'start_time', 'end_time', 'created_at', 'updated_at', 'schedule_type', 'location_id', 'stdcrsid','total_hours','remaining_hours'], 'safe'],           
            [['city', 'state', 'zip', 'country'], 'string', 'max' => 100],
            ['schedule_type', 'unique', 'targetAttribute' => ['admin_id', 'lesson_id', 'instructor_id', 'schedule_date', 'start_time', 'end_time', 'schedule_type', 'isDeleted'], 'message' => 'Combination already Exist!!!']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'schedule_id' => 'Schedule ID',
            'lesson_id' => 'Lesson',
            'instructor_id' => 'Instructor',
            'schedule_date' => 'Schedule Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'country' => 'Country',
            'schedule_type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'stdcrsid' => 'Student Name',
            'sch_status' => 'Schedule Status'
        ];
    }

    public function behaviors() {
        return [
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
                'replaceRegularDelete' => true // mutate native `delete()` method
            ],
        ];
    }

    public static function find() {
        $session = Yii::$app->session;
          
        $query = parent::find()->where(['db_schedules.isDeleted' => false]);

        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['db_schedules.admin_id' => $adminid, 'db_schedules.isDeleted' => false]);
        }

        if ($session->has('cityid')) {         
            $query->andWhere(['db_schedules.city_id' => $session->get('cityid')]);
        }
        
        return $query;
    }

    public function getLesson() {
        return $this->hasOne(DlLessons::className(), ['lesson_id' => 'lesson_id']);
    }

    public function getInstructor() {
        return $this->hasOne(DlInstructors::className(), ['instructor_id' => 'instructor_id']);
    }
    public function getDlStudentCourses() {
        return $this->hasMany(DlStudentCourse::className(), ['schedule_id' => 'schedule_id']);
    }
    public function getLocation() {
        return $this->hasOne(DlLocations::className(), ['location_id' => 'location_id']);
    }

    public function getLocationaddress() {
        $sinfo = "";

        if ($this->location->address1 != "") {
            $sinfo .= $this->location->address1;
        }

        if ($this->location->address2 != "") {
            $sinfo .= ", " . $this->location->address2;
        }

        if ($this->location->city != "") {
            $sinfo .= ", " . $this->location->city;
        }

        if ($this->location->state != "") {
            $sinfo .= ", " . $this->location->state;
        }

        if ($this->location->zip != "") {
            $sinfo .= ", " . $this->location->zip;
        }


        return $sinfo;
    }

}
