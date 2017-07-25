<?php

namespace common\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dl_admin_cities".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $city_id
 * @property string $created_at
 */
class DlAdminCities extends ActiveRecord {

    public $citylist;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_admin_cities';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['admin_id', 'city_id'], 'integer'],
            [['citylist'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'admin_id' => 'Admin ID',
            'city_id' => 'City ID',           
            'citylist' => 'Client Cities'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCities() {
        return $this->hasOne(DlCity::className(), ['city_id' => 'city_id']);
    }   

}
