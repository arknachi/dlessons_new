<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dl_settings".
 *
 * @property integer $setting_id
 * @property string $option_name
 * @property string $option_value
 * @property string $option_type
 * @property string $updated_at
 */
class DlSettings extends \yii\db\ActiveRecord
{
    public $dashboard;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dl_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_name', 'option_value', 'option_type', 'updated_at','dashboard'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dashboard' => 'Dashboard Display'            
        ];
    }
}
