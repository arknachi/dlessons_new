<?php

namespace common\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dl_lessons".
 *
 * @property integer $lesson_id
 * @property integer $admin_id
 * @property string $lesson_name
 * @property string $lesson_desc
 * @property string $created_at
 * @property string $updated_at
 */
class DlLessons extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_lessons';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['lesson_name', 'lesson_desc'], 'required'],
            [['admin_id'], 'integer'],
            [['lesson_desc'], 'string'],
            [['created_at', 'updated_at' , 'hours'], 'safe'],
            [['lesson_name'], 'string', 'max' => 55],
            ['lesson_name', 'unique', 'targetAttribute' => ['admin_id', 'lesson_name', 'isDeleted'], 'message' => 'Lesson already Exist!!!']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'lesson_id' => 'Lesson',
            'admin_id' => 'Admin',
            'lesson_name' => 'Lesson Name',
            'lesson_desc' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    public function getAdminlessons() {
        return $this->hasOne(DlAdminLessons::className(), ['lesson_id' => 'lesson_id']);
    }

    public static function find() {
        $query = parent::find()->where(['isDeleted' => false]);

        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['admin_id' => $adminid, 'isDeleted' => false]);
        }

        return $query;
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

}
