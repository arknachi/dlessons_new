<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dl_ins_available_days".
 *
 * @property integer $available_id
 * @property integer $instructor_id
 * @property string $available_date
 * @property string $start_time
 * @property string $end_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updates_at
 */
class DlInsAvailableDays extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dl_ins_available_days';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [            
            [['available_id', 'instructor_id', 'created_by', 'updated_by'], 'integer'],
            [['available_id', 'instructor_id', 'available_date', 'start_time', 'end_time', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'available_id' => 'Available ID',
            'instructor_id' => 'Instructor ID',
            'available_date' => 'Available Date',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updates At',
        ];
    }
    
    public function beforeSave($insert) {
        
        if ($this->isNewRecord) {
            $this->created_at = date("Y-m-d H:i:s", time());
            $this->created_by = Yii::$app->user->identity->id;
        } else {            
            $this->updated_by = Yii::$app->user->identity->id;
        }
        
        $this->updated_at = date('Y-m-d H:i:s', time());
        
        return parent::beforeSave($insert);
    }
    
    public function getInstructor() {
        return $this->hasOne(DlInstructors::className(), ['instructor_id' => 'instructor_id']);
    }
    
//    public static function find() {
//       $query = parent::find()->joinWith('instructor');
//        if (isset(Yii::$app->user->identity->ParentAdminId) && $adminid = Yii::$app->user->identity->ParentAdminId) {
//            $query->where(['dl_instructors.admin_id' => $adminid , 'isDeleted' => false]);
//        }
//       return $query;
//    }
}
