<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dl_payment".
 *
 * @property integer $payment_id
 * @property integer $scr_id
 * @property double $payment_amount
 * @property string $payment_type
 * @property string $payment_trans_id
 * @property integer $payment_status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DlStudent $student
 * @property DlStudentCourse $scr
 */
class DlPayment extends ActiveRecord {
    
    public static $payment_types = ["cheque" => "Check","CC" => "Credit Card", "cash" => "Cash"];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['scr_id', 'payment_date' , 'payment_amount', 'payment_type', 'created_at'], 'required'],
            [['scr_id', 'payment_status'], 'integer'],
            [['payment_amount'], 'number'],
            [['created_at', 'updated_at','payment_trans_id','credit_card_type','client_ip','payment_notes','cheque_no','payment_date','created_by','updated_by','role'], 'safe'],          
            [['scr_id'], 'exist', 'skipOnError' => true, 'targetClass' => DlStudentCourse::className(), 'targetAttribute' => ['scr_id' => 'scr_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_id' => 'Payment ID',
            'scr_id' => 'Student Course',
            'payment_amount' => 'Payment Amount',
            'payment_type' => 'Payment Type',
            'payment_trans_id' => 'Payment Trans ID',
            'payment_status' => 'Payment Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'credit_card_type' => 'Card Type',
            'payment_date' => "Payment Date",
            'cheque_no' => "Check Number"
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getScr() {
        return $this->hasOne(DlStudentCourse::className(), ['scr_id' => 'scr_id']);
    }

    public static function find() {
        $query = parent::find();
        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['dl_student_course.admin_id' => $adminid]);
            $query->joinWith('scr');
        }
        return $query;
    }
    
    public function beforeSave($insert) {
        if(Yii::$app->user->identity->role=="admin")
        {
             $this->role = 2;
        }else{
             $this->role = 1;
        }   
        
        if ($this->isNewRecord) {
            $this->created_by = Yii::$app->user->getId();
        }else{
            $this->updated_by = Yii::$app->user->getId();
        }

        return parent::beforeSave($insert);
    }

}
