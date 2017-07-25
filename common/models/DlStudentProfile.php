<?php

namespace common\models;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dl_student_profile".
 *
 * @property integer $std_prof_id
 * @property integer $student_id
 * @property string $gender
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $phone
 * @property string $dob
 * @property string $permit_num
 * @property string $language
 * @property string $hear_about_this
 * @property string $referred_by
 * @property string $payer_firstname
 * @property string $payer_lastname
 * @property string $payer_address1
 * @property string $payer_address2
 * @property string $payer_city
 * @property string $payer_state
 * @property string $payer_zip
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DlStudent $student
 */
class DlStudentProfile extends ActiveRecord {

    public static $genderList = ['M' => 'Male', 'F' => 'Female'];
    public static $langList = ['en' => 'English', 'es' => 'Spanish'];
    public static $hearAbout = ['se' => 'Search Engine', 'fr' => 'Friends', 'ad' => 'Advertisement'];
    public $exp_month,$exp_year,$card_cvv,$card_num;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_student_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
           // [['gender', 'address1', 'city', 'state', 'zip', 'phone', 'dob'], 'required'],
            [['payer_firstname', 'payer_lastname', 'payer_address1', 'payer_city', 'payer_state', 'payer_zip','card_cvv','card_num'], 'required', 'on' => 'payment'],
            [['student_id'], 'integer'],
            [['dob'], 'default', 'value' => null],
            [['dob', 'created_at', 'updated_at','exp_month','card_cvv','card_num','exp_year'], 'safe'],
            [['gender'], 'string', 'max' => 1],
            [['address1', 'address2', 'permit_num', 'hear_about_this', 'referred_by', 'payer_address1', 'payer_address2'], 'string', 'max' => 255],
            [['city', 'state', 'zip', 'phone', 'payer_firstname', 'payer_lastname'], 'string', 'max' => 55],
            [['language'], 'string', 'max' => 10],
            [['payer_city', 'payer_state', 'payer_zip'], 'string', 'max' => 50],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => DlStudent::className(), 'targetAttribute' => ['student_id' => 'student_id']],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dob'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'dob',
                ],
                'value' => function ($event) {
                    if($this->dob!="")
                        return date('Y-m-d', strtotime($this->dob));
                    else
                        return "";    
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'std_prof_id' => 'Std Prof ID',
            'student_id' => 'Student ID',
            'gender' => 'Gender',
            'address1' => 'Address 1',
            'address2' => 'Address 2',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'phone' => 'Cell Phone',
            'dob' => 'Date of Birth',
            'permit_num' => 'Permit Num',
            'language' => 'Language',
            'hear_about_this' => 'Hear About This',
            'referred_by' => 'Referred By',
            'payer_firstname' => 'First Name (Cardholder)',
            'payer_lastname' => 'Last Name (Cardholder)',
            'payer_address1' => 'Address 1',
            'payer_address2' => 'Address 2',
            'payer_city' => 'City',
            'payer_state' => 'State',
            'payer_zip' => 'Zip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'exp_month' => "Exp Month",
            'exp_year' => 'Exp Year',
            'card_num' => 'Card Number',
            'card_cvv' => 'Cvv'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStudent() {
        return $this->hasOne(DlStudent::className(), ['student_id' => 'student_id']);
    }
    
    public function beforeSave($insert) {        
        if($this->isNewRecord){
             $this->created_at = date("Y-m-d h:i:s",time());
        }

        return parent::beforeSave($insert);
    }

}
