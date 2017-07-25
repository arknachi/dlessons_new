<?php

namespace common\models;

use common\components\Myclass;
use common\models\DlPayment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DlPaymentSearch represents the model behind the search form about `common\models\DlPayment`.
 */
class DlPaymentSearch extends DlPayment {

    public $startdate, $enddate;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_type', 'payment_trans_id', 'created_at', 'updated_at', 'startdate', 'enddate','payment_status'], 'safe'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'startdate' => 'Start Date',
            'enddate' => 'End Date'
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
        
        $datamod = array();
        $datamod = $_GET;
        // add conditions that should always apply here
        $this->load($params);
      
        $query = DlPayment::find();
        
        if ($this->startdate != "" && $this->enddate != "") {
            $query->where('DATE_FORMAT(dl_payment.created_at,"%Y-%m-%d") >= "'.Myclass::dateformat($this->startdate).'" AND DATE_FORMAT(dl_payment.created_at,"%Y-%m-%d") <= "'.Myclass::dateformat($this->enddate).'"');           
            $datamod['DlPaymentSearch']['startdate'] = Myclass::dateformat($this->startdate);
            $datamod['DlPaymentSearch']['enddate'] = Myclass::dateformat($this->enddate);
        }
        
        if($this->payment_status){        
            $query->andWhere(['dl_payment.payment_status'=>'1']);
        }
        
       //echo $query->createCommand()->getRawSql();exit;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => 'DESC']],
            'pagination' => [
                'params' => $datamod
            ],
        ]);       

        return $dataProvider;
    }

}
