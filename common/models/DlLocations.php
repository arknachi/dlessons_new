<?php

namespace common\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dl_locations".
 *
 * @property integer $location_id
 * @property integer $admin_id
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zip
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $isDeleted
 *
 * @property DbSchedules[] $dbSchedules
 */
class DlLocations extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dl_locations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'address1'], 'required'],
            [['admin_id', 'created_by', 'updated_by', 'isDeleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['address1', 'address2', 'city', 'state'], 'string', 'max' => 255],
            [['country', 'zip'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'location_id' => 'Location',
            'admin_id' => 'Admin ID',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'zip' => 'Zip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'isDeleted' => 'Is Deleted',
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
       $query = parent::find()->where(['isDeleted' => false]);
        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['admin_id' => $adminid , 'isDeleted' => false]);
        }
       return $query;
    }

    /**
     * @return ActiveQuery
     */
    public function getDbSchedules()
    {
        return $this->hasMany(DbSchedules::className(), ['location_id' => 'location_id']);
    }
    
     public function getAdmin() {
        return $this->hasMany(DlAdmin::className(), ['admin_id' => 'admin_id']);
    }
}
