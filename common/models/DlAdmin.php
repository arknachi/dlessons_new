<?php

namespace common\models;

use cornernote\linkall\LinkAllBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "dl_admin".
 *
 * @property integer $admin_id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $field1
 * @property string $field2
 * @property string $field3
 * @property string $field4
 * @property string $client_name
 * @property string $domain_url
 * @property integer $status
 * @property string $remember_token
 * @property string $created_at
 * @property string $modified_at
 */
class DlAdmin extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $oldpass;
    public $newpass;
    public $repeatnewpass;
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // ['username', 'required', 'message' => 'Please choose a username.'],
            [['username', 'email', 'first_name', 'last_name'], 'required'],
            [['company_code', 'company_name', 'cell_phone', 'password'], 'required', 'on' => 'createadmin'],
            [['password'], 'required', 'on' => 'createsubadmin'],
            [['status'], 'integer'],
            [['instructor_schedule_status', 'modified_at', 'updated_at', 'website', 'address1', 'address2', 'company_code', 'company_name', 'city', 'state', 'zip', 'work_phone', 'cell_phone', 'notes', 'auth_key', 'first_name', 'last_name', 'disclaimer', 'privacy', 'facebook_url'], 'safe'],
            [['password'], 'string', 'max' => 255],
            [['disclaimer', 'privacy'], 'string'],
            [['email'], 'string', 'max' => 75],
            [['company_name', 'city', 'state', 'remember_token', 'username'], 'string', 'max' => 100],
            ['email', 'email'],
            [['email', 'company_code', 'username'], 'unique'],
            [['oldpass', 'newpass', 'repeatnewpass'], 'required', 'on' => 'changepassword'],
            ['oldpass', 'findPasswords', 'on' => 'changepassword'],
            ['repeatnewpass', 'compare', 'compareAttribute' => 'newpass', 'on' => 'changepassword'],
            [['logo'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png,jpg,jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'User ID',
            'email' => 'Email',
            'password' => 'Password',
            'modified_at' => 'Modified At',
            'remember_token' => 'Remember Token',
            'status' => 'Status',
            'address1' => "Address 1",
            'address2' => "Address 2",
            'company_name' => "Company Name",
            'website' => 'Website',
            'city' => "City",
            'state' => "State",
            'zip' => "Zip",
            'work_phone' => "Work Phone",
            'cell_phone' => "Cell Phone",
            'notes' => "Notes",
            'company_code' => "Company Code",
            'created_at' => 'Created At',
            'first_name' => "First Name",
            'last_name' => 'Last Name',
            'oldpass' => 'Old Password',
            'newpass' => 'New Password',
            'repeatnewpass' => 'Repeat New Password',
            'imageFile' => "Upload Logo",
            'disclaimer' => 'Disclaimer',
            'privacy' => 'Privacy',
            'facebook_url' => 'Facebook URL',
            'instructor_schedule_status' => "Schedule create access for Instructors"
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $extend = [
            LinkAllBehavior::className(),
        ];

        $behaviour = array_merge(parent::behaviors(), $extend);
        return $behaviour;
    }

    public function getParentAdminId() {
        return ($this->parent_id > 0) ? $this->parent_id : $this->admin_id;
    }

    public function getParentId() {
        return $this->parent_id;
    }
    
    public function getRole() {
        return "admin";
    }

    public function getMystudents() {
        return $this->hasMany(DlStudent::className(), ['student_id' => 'student_id'])->via('studentCourses');
    }

    public function getMyPayments() {
        return $this->hasMany(DlPayment::className(), ['scr_id' => 'scr_id'])->via('studentCourses');
    }

    public function getStudentCourses() {
        return $this->hasMany(DlStudentCourse::className(), ['admin_id' => 'admin_id']);
    }

    public function getAdminLessons() {
        return $this->hasMany(DlAdminLessons::className(), ['admin_id' => 'admin_id']);
    }

    public function getLessons() {
        return $this->hasMany(DlLessons::className(), ['lesson_id' => 'lesson_id'])->via('adminLessons');
    }

    public function getAdminCities() {
        return $this->hasMany(DlAdminCities::className(), ['admin_id' => 'admin_id']);
    }

    public function getCities() {
        return $this->hasMany(DlCity::className(), ['city_id' => 'city_id'])->via('adminCities');
    }

    public function getFullname() {
        return trim($this->first_name . " " . $this->last_name);
    }

    public function findPasswords($attribute, $params) {
        $user = self::find()->where([
                    'admin_id' => Yii::$app->user->getId()
                ])->one();
        $password = $user->password;

        if ($password != Yii::$app->myclass->refencryption($this->oldpass))
            $this->addError($attribute, 'Old password is incorrect');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['admin_id' => $id, 'status' => self::STATUS_ACTIVE]);
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

        return parent::beforeSave($insert);
    }

}
