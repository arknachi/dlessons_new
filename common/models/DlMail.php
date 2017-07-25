<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dl_mail".
 *
 * @property integer $mail_id
 * @property integer $admin_id
 * @property string $name
 * @property integer $is_active
 * @property integer $sent_to
 * @property string $format
 * @property string $mail_title
 * @property string $mail_body_text
 * @property string $mail_body_html
 * @property string $mail_from
 * @property string $mail_from_name
 * @property string $mail_bcc
 */
class DlMail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_mail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mail_title', 'mail_from'], 'required'],
            ['mail_body_html', 'either', 'params' => ['other' => 'mail_body_text']],
            [['admin_id', 'is_active', 'sent_to'], 'integer'],
            [['mail_body_text', 'mail_body_html'], 'string'],
            [['name'], 'string', 'max' => 40],
            [['format'], 'string', 'max' => 20],
            [['mail_title', 'mail_from', 'mail_from_name'], 'string', 'max' => 120],
            [['mail_bcc'], 'string', 'max' => 250],
            [['name'], 'unique'],
        ];
    }

    public function either($attribute_name, $params) {
        $field1 = $this->getAttributeLabel($attribute_name);
        $field2 = $this->getAttributeLabel($params['other']);
        if (empty($this->$attribute_name) && empty($this->{$params['other']})) {
            $this->addError($attribute_name, "Either {$field1} or {$field2} is required.");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mail_id' => 'Mail ID',
            'admin_id' => 'Admin ID',
            'name' => 'Name',
            'is_active' => 'Status',
            'sent_to' => 'Sent To',
            'format' => 'Format',
            'mail_title' => 'Subject',
            'mail_body_text' => 'Mail Body Text',
            'mail_body_html' => 'Mail Body Html',
            'mail_from' => 'From Email',
            'mail_from_name' => 'From Name',
            'mail_bcc' => 'Bcc',
        ];
    }

    public static function find() {
        $query = parent::find();

        if (isset(Yii::$app->user->identity->ParentAdminId)) {
            $adminid = Yii::$app->user->identity->ParentAdminId;
            $query->where(['dl_mail.admin_id' => $adminid]);
        }

        return $query;
    }

}
