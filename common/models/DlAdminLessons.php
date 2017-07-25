<?php

namespace common\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dl_admin_lessons".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $lesson_id
 * @property string $price
 * @property string $disclaimer
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class DlAdminLessons extends ActiveRecord {

    public $clist, $ctitle, $lessonnameFilter;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dl_admin_lessons';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['price'], 'required'],
            [['admin_id', 'lesson_id', 'status'], 'integer'],
            [['price'], 'number'],            
            [['created_at', 'clist', 'ctitle', 'lessonnameFilter'], 'safe'],
            [['updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'admin_id' => 'Admin',
            'lesson_id' => 'Lesson',
            'price' => 'Price',         
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'clist' => "Lessons",
            'ctitle' => "Title"
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getLessons() {
        return $this->hasOne(DlLessons::className(), ['lesson_id' => 'lesson_id']);
    }

    public function search($params, $adminid) {
        $query = DlAdminLessons::find()->joinWith('lessons');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //  'lesson_id' => $this->lesson_id,
            'dl_admin_lessons.admin_id' => $adminid,
        ]);

        $query->andFilterWhere(['like', 'dl_lessons.lesson_name', $this->lessonnameFilter]);

        return $dataProvider;
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created_at = date("Y-m-d H:i:s", time());
        } else {
            $this->updated_at = date('Y-m-d H:i:s', time());
        }

        return parent::beforeSave($insert);
    }

}
