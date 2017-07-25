<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "db_city".
 *
 * @property integer $city_id
 * @property string $city_name
 */
class DlCity extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'db_city';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['city_name'], 'required'],
            [['city_name'], 'unique'],
            [['city_name'], 'string', 'max' => 55],
        ];
    }
    
    public function getAdmincities() {
        return $this->hasOne(DlAdminCities::className(), ['city_id' => 'city_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'city_id' => 'City ID',
            'city_name' => 'City Name',
        ];
    }

}
