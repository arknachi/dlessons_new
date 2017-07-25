<?php

namespace common\models;

use common\components\Myclass;
use common\models\DbSchedules;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DbSchedulesSearch represents the model behind the search form about `common\models\DbSchedules`.
 */
class DbSchedulesSearch extends DbSchedules {

    public $startdate, $enddate;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['schedule_id', 'lesson_id', 'instructor_id', 'created_by', 'updated_by'], 'integer'],
            [['schedule_date', 'start_time', 'end_time', 'city', 'state', 'zip', 'country', 'status', 'created_at', 'updated_at', 'startdate', 'enddate'], 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'startdate' => 'Start Date',
            'enddate' => 'End Date',
            'instructor_id' => 'Instructor'
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = DbSchedules::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['schedule_date' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'schedule_id' => $this->schedule_id,
            'lesson_id' => $this->lesson_id,
            'instructor_id' => $this->instructor_id,
            'schedule_date' => $this->schedule_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'status' => $this->status
        ]);

        return $dataProvider;
    }

    public function Ins_schedules_search($params, $insid) {
        $query = DbSchedules::find();

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
            'schedule_id' => $this->schedule_id,
            'lesson_id' => $this->lesson_id,
            'instructor_id' => $insid,
            'schedule_date' => $this->schedule_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    public function search_ins_hours($params) {
        $session = Yii::$app->session;
        $datamod = array();
        $datamod = $_GET;
        // add conditions that should always apply here

        $this->load($params);

        $query = DbSchedules::find();

        if ($this->startdate != "" && $this->enddate != "") {
            $query->where('DATE_FORMAT(db_schedules.schedule_date,"%Y-%m-%d") >= "' . Myclass::dateformat($this->startdate) . '" AND DATE_FORMAT(db_schedules.schedule_date,"%Y-%m-%d") <= "' . Myclass::dateformat($this->enddate) . '"');
            $datamod['DbSchedulesSearch']['startdate'] = Myclass::dateformat($this->startdate);
            $datamod['DbSchedulesSearch']['enddate'] = Myclass::dateformat($this->enddate);
        }

        if ($this->instructor_id) {
            $query->andWhere(['db_schedules.instructor_id' => $this->instructor_id]);
        }
        
        if ($session->has('cityid')) {         
            $query->andWhere(['dl_instructors.city_id' => $session->get('cityid')]);
        }
        
        $query->joinWith('instructor');
                
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['schedule_date' => 'DESC']],
            'pagination' => [
                'params' => $datamod
            ],
        ]);

        return $dataProvider;
    }

}
