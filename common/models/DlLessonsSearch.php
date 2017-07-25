<?php

namespace common\models;

use common\models\DlLessons;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DlLessonsSearch represents the model behind the search form about `common\models\DlLessons`.
 */
class DlLessonsSearch extends DlLessons {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['lesson_id', 'admin_id'], 'integer'],
            [['lesson_name', 'lesson_desc', 'created_at', 'updated_at'], 'safe'],
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
        $query = DlLessons::find();

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
            'lesson_id' => $this->lesson_id,
            'admin_id' => $this->admin_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'lesson_name', $this->lesson_name])
                ->andFilterWhere(['like', 'lesson_desc', $this->lesson_desc]);

        return $dataProvider;
    }

}
