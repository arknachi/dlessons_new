<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "dl_student".
 *
 * @property integer $student_id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $isDeleted
 *
 * @property DlPayment[] $dlPayments
 * @property DlAdmin $admin
 * @property DlStudentCourse[] $dlStudentCourses
 * @property DlStudentProfile[] $dlStudentProfiles
 */
class DlStudent extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $password_repeat;
    public $searchstatus,$searchlesson;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_student';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'first_name', 'last_name', 'email'], 'required'],
            [['password'], 'required','on' => 'create'],
            ['password_repeat', 'required', 'on' => 'create'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match", 'on' => 'create'],
            [['username'], 'unique'],
            [['status', 'isDeleted'], 'integer'],
            [['password','created_at', 'updated_at','searchstatus','searchlesson'], 'safe'],
            [['username'], 'string', 'max' => 100],
            [['password', 'email', 'first_name', 'middle_name', 'last_name'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'student_id' => 'Student ID',
            'username' => 'Username',
            'password' => 'Password',
            'password_repeat' => 'Confirm Password',
            'email' => 'Email',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Initial',
            'last_name' => 'Last Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isDeleted' => 'Is Deleted',
            'searchstatus' => "Status",
            "student_dob" => "Date Of Birth",
            'searchlesson' => 'Lessons'
        ];
    }

    public function getFullname() {
        return trim("{$this->first_name} {$this->middle_name}  {$this->last_name}");
    }
    
    public function getRole() {
        return "student";
    }

    public function getDlPayments($aid = null, $sid = null) {
        return $this->hasMany(DlPayment::className(), ['scr_id' => 'scr_id'])->via('dlStudentCourses');
    }

    public function getDlStudentCourses() {
        return $this->hasMany(DlStudentCourse::className(), ['student_id' => 'student_id']);
    }

    public function getStudentProfile() {
        return $this->hasOne(DlStudentProfile::className(), ['student_id' => 'student_id']);
    }

//    public static function find() {
//        $query = parent::find();
//        if (isset(Yii::$app->user->identity->ParentAdminId) && $adminid = Yii::$app->user->identity->ParentAdminId) {
//            $query->where(['admin_id' => $adminid, 'isDeleted' => false]);
//        }
//        return $query;
//    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['student_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public function beforeSave($insert) {

        if (isset($this->password) && $this->password != "") {
            $this->password = Yii::$app->myclass->refencryption($this->password);
        }
        
        if($this->isNewRecord){
             $this->created_at = date("Y-m-d h:i:s",time());
        }

        return parent::beforeSave($insert);
    }

}
