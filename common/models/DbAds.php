<?php

namespace common\models;

use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "db_ads".
 *
 * @property integer $ads_id
 * @property integer $lesson_id
 * @property integer $admin_id
 * @property string $image
 * @property string $url
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class DbAds extends ActiveRecord {

    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'db_ads';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['lesson_id', 'admin_id', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'image', 'imageFile'], 'safe'],
            [['image', 'url'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,jpg,jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ads_id' => 'Ads ID',
            'lesson_id' => 'Lesson',
            'admin_id' => 'Admin',
            'image' => 'Image',
            'url' => 'Url',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'imageFile' => "Upload Image"
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
        //  return parent::find()->where(['isDeleted' => false]);
        $query = parent::find(['isDeleted' => false]);

        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['admin_id' => $adminid, 'isDeleted' => false]);
        }
        return $query;
    }

    public function getLesson() {
        return $this->hasOne(DlLessons::className(), ['lesson_id' => 'lesson_id']);
    }

    public function getAdminlesson() {
        return $this->hasOne(DlAdminLessons::className(), ['admin_id' => 'admin_id', 'lesson_id' => 'lesson_id']);
    }

    public function getAdmin() {
        return $this->hasOne(DlAdmin::className(), ['admin_id' => 'admin_id']);
    }

    public function getAdminlogo() {
        if ($this->admin->logo != "")
            return "uploads/logos/" . $this->admin->logo;
        else
            return false;
    }

    public function getBookingurl() {
        $search = ['/webpanel'];
        $replace = [''];
        //echo Url::to(['/affiliate/booking/index', 'aid' => $this->ads_id]); exit;
        return str_replace($search, $replace, Url::to(['/affiliate/booking/index', 'aid' => $this->ads_id], true));
    }

    public function getAdimage() {

        if (Yii::$app->request->serverName == "local.dlessons")
            $image_url = Url::to($this->image, true);
        else
            $image_url = Url::to("/" . $this->image, true);

        // return "<img src='{$image_url}' width='300' height='150' title='{$this->lesson->lesson_name}' alt='{$this->lesson->lesson_name}' />";
        return "<img src='{$image_url}' title='{$this->lesson->lesson_name}' alt='{$this->lesson->lesson_name}' />";
    }

    public function getAdcode() {
        $result = "<div><a href='{$this->bookingurl}'>{$this->adimage}</a><br><a href='{$this->bookingurl}'>{$this->lesson->lesson_name}</a></div>";
        $htmlres = htmlentities($result);
        return "<textarea rows='5' cols='50'>{$htmlres}</textarea>";
    }

}
