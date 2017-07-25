<?php

namespace common\models;

use cornernote\linkall\LinkAllBehavior;
use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "dl_instructors".
 *
 * @property integer $instructor_id
 * @property integer $admin_id
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $address1
 * @property string $address2
 * @property string $website
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $work_phone
 * @property string $cell_phone
 * @property string $notes
 * @property integer $status
 * @property string $remember_token
 * @property string $created_at
 * @property string $modified_at
 * @property string $auth_key
 * @property string $updated_at
 */
class DlInstructors extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $oldpass;
    public $newpass;
    public $repeatnewpass;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_instructors';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['first_name', 'username', 'email', 'cell_phone'], 'required'],
            [['password'], 'required', 'on' => 'create'],
            [['admin_id', 'status'], 'integer'],
            [['notes'], 'string'],
            [['created_at', 'modified_at'], 'safe'],
            [['username', 'remember_token'], 'string', 'max' => 100],
            [['password', 'first_name', 'last_name', 'auth_key', 'updated_at'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 75],
            [['address1', 'address2', 'website'], 'string', 'max' => 200],
            [['city', 'state', 'zip', 'work_phone', 'cell_phone'], 'string', 'max' => 55],
            ['email', 'email'],
            [['username', 'email'], 'unique'],
            [['oldpass', 'newpass', 'repeatnewpass'], 'required', 'on' => 'changepassword'],
            ['oldpass', 'findPasswords', 'on' => 'changepassword'],
            ['repeatnewpass', 'compare', 'compareAttribute' => 'newpass', 'on' => 'changepassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'instructor_id' => 'Instructor ID',
            'admin_id' => 'Admin ID',
            'username' => 'Instructor Username',
            'password' => 'Password',
            'fullname' => 'Instructor',
            'first_name' => 'Instructor First Name',
            'last_name' => 'Instructor Last Name',
            'email' => 'Email',
            'address1' => 'Address1',
            'address2' => 'Address2',
            'website' => 'Website',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'work_phone' => 'Work Phone',
            'cell_phone' => 'Cell Phone',
            'notes' => 'Notes',
            'status' => 'Status',
            'remember_token' => 'Remember Token',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'auth_key' => 'Auth Key',
            'updated_at' => 'Updated At',
            'oldpass' => 'Old Password',
            'newpass' => 'New Password',
            'repeatnewpass' => 'Repeat New Password',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $extend = [
            LinkAllBehavior::className(),
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
                'replaceRegularDelete' => true // mutate native `delete()` method
            ]
        ];

        $behaviour = array_merge(parent::behaviors(), $extend);
        return $behaviour;
    }

    public static function find() {
        $session = Yii::$app->session;
         
        $query = parent::find(['dl_instructors.isDeleted' => false]);
        
        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['dl_instructors.admin_id' => $adminid, 'dl_instructors.isDeleted' => false]);
        }
        
        if ($session->has('cityid')) {         
            $query->andWhere(['dl_instructors.city_id' => $session->get('cityid')]);
        }
        
        return $query;
    }

    public function getAdmin() {
        return $this->hasMany(DlAdmin::className(), ['admin_id' => 'admin_id']);
    }
    
    public function getInsavailabledays() {
        return $this->hasMany(DlInsAvailableDays::className(), ['instructor_id' => 'instructor_id']);
    }

    public function getFullname() {
        return trim($this->first_name . " " . $this->last_name);
    }

    public function getAdminid() {
        return $this->admin_id ;
    }
    
    public function getInscityid()
    {
        return $this->city_id;
    }
    
     public function getAccessschedule() {
         $amodel = DlAdmin::findOne($this->admin_id);
         return $amodel->instructor_schedule_status;
    }
    
    public function findPasswords($attribute, $params) {
        $user = self::find()->where([
                    'instructor_id' => Yii::$app->user->getId()
                ])->one();
        $password = $user->password;

        if ($password != Yii::$app->myclass->refencryption($this->oldpass))
            $this->addError($attribute, 'Old password is incorrect');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['instructor_id' => $id, 'status' => self::STATUS_ACTIVE]);
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
        
        if ($this->isNewRecord) {
            //$this->created_at = date("Y-m-d H:i:s", time());
            $this->created_by = Yii::$app->user->identity->id;
        } else {            
            $this->updated_by = Yii::$app->user->identity->id;
        }

        return parent::beforeSave($insert);
    }

}
