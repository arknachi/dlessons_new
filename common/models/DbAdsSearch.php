<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DbAds;

/**
 * DbAdsSearch represents the model behind the search form about `common\models\DbAds`.
 */
class DbAdsSearch extends DbAds
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ads_id', 'lesson_id', 'admin_id', 'created_by', 'updated_by', 'isDeleted'], 'integer'],
            [['image', 'url', 'content', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = DbAds::find();

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
//            'ads_id' => $this->ads_id,
//            'lesson_id' => $this->lesson_id,
              'admin_id' => $this->admin_id, 
        ]);
//
//        $query->andFilterWhere(['like', 'image', $this->image])
//            ->andFilterWhere(['like', 'url', $this->url])
//            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
