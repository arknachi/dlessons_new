<?php

namespace common\models;

use common\models\DlAdmin;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DlAdminSearch represents the model behind the search form about `common\models\DlAdmin`.
 */
class DlAdminSearch extends DlAdmin {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['admin_id', 'status'], 'integer'],
            [['username', 'password', 'email', 'modified_at', 'updated_at', 'address1', 'address2', 'company_code', 'website', 'company_name', 'city', 'state', 'zip', 'work_phone', 'cell_phone', 'notes', 'auth_key','first_name','last_name'], 'safe'],
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
        $query = DlAdmin::find();
        $query->where('parent_id = 0');

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
            'admin_id' => $this->admin_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'address1', $this->address1])
                ->andFilterWhere(['like', 'address2', $this->address2])
                ->andFilterWhere(['like', 'company_name', $this->company_name])
                ->andFilterWhere(['like', 'company_code', $this->company_code])
                ->andFilterWhere(['like', 'website', $this->website])
                ->andFilterWhere(['like', 'city', $this->city])
                ->andFilterWhere(['like', 'state', $this->state])
                ->andFilterWhere(['like', 'work_phone', $this->work_phone])
                ->andFilterWhere(['like', 'cell_phone', $this->cell_phone]);

        return $dataProvider;
    }

    public function searchsubadmins($params) {
        $query = DlAdmin::find();
        $query->where('parent_id = ' . Yii::$app->user->identity->ParentAdminId);

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
            'admin_id' => $this->admin_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'first_name', $this->first_name])
                ->andFilterWhere(['like', 'last_name', $this->last_name])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'address1', $this->address1])
                ->andFilterWhere(['like', 'address2', $this->address2])
                ->andFilterWhere(['like', 'company_name', $this->company_name])
                ->andFilterWhere(['like', 'company_code', $this->company_code])
                ->andFilterWhere(['like', 'website', $this->website])
                ->andFilterWhere(['like', 'city', $this->city])
                ->andFilterWhere(['like', 'state', $this->state])
                ->andFilterWhere(['like', 'work_phone', $this->work_phone])
                ->andFilterWhere(['like', 'cell_phone', $this->cell_phone]);

        return $dataProvider;
    }

}
