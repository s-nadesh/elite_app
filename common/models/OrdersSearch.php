<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `common\models\Orders`.
 */
class OrdersSearch extends Orders {

    public $user;
    public $ordered_by_name;
    public $started_at;
    public $ended_at;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user', 'ordered_by_name', 'started_at', 'ended_at'], 'safe'],
            [['order_id', 'user_id', 'order_status_id', 'ordered_by', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['invoice_no', 'invoice_date', 'payment_status', 'signature'], 'safe'],
            [['items_total_amount', 'tax_percentage', 'tax_amount', 'total_amount'], 'number'],
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
        // create ActiveQuery
        $query = Orders::find();
        $query->joinWith(['user us', 'orderedBy se']); //JOIN with alias
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]],
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "OrdersSearch" instance
        $dataProvider->sort->attributes['user'] = [
            // The tables are the ones our relation are configured to
            'asc' => ['us.name' => SORT_ASC],
            'desc' => ['us.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['ordered_by_name'] = [
            'asc' => ['se.name' => SORT_ASC],
            'desc' => ['se.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->started_at && $this->ended_at) {
//            $query->andFilterWhere(['between', 'invoice_date', $this->started_at, $this->ended_at]);
            $query->andWhere('DATE_FORMAT(invoice_date,"%Y-%m-%d") >= "' . Orders::dateformat($this->started_at) . '" AND DATE_FORMAT(invoice_date,"%Y-%m-%d") <= "' . Orders::dateformat($this->ended_at) . '"');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'invoice_date' => $this->invoice_date,
            'user_id' => $this->user_id,
            'order_status_id' => $this->order_status_id,
            'ordered_by' => $this->ordered_by,
            'items_total_amount' => $this->items_total_amount,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            'el_orders.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
        ]);
        $query->andFilterWhere(['like', 'invoice_no', $this->invoice_no])
                ->andFilterWhere(['like', 'total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'payment_status', $this->payment_status])
                ->andFilterWhere(['like', 'us.name', $this->user])
                ->andFilterWhere(['like', 'se.name', $this->ordered_by_name]);

        return $dataProvider;
    }

     public function search_tv_view($params) {
        // create ActiveQuery
        $query = Orders::find();
//        $query->joinWith(['orderItems oi']); //JOIN with alias
        
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => 10),
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]],
        ]);
      

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'invoice_date' => $this->invoice_date,
            'user_id' => $this->user_id,
            'order_status_id' => $this->order_status_id,
            'ordered_by' => $this->ordered_by,
            'items_total_amount' => $this->items_total_amount,
            'tax_percentage' => $this->tax_percentage,
            'tax_amount' => $this->tax_amount,
            'el_orders.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
        ]);
        $query->andFilterWhere(['like', 'invoice_no', $this->invoice_no])
                ->andFilterWhere(['like', 'total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'payment_status', $this->payment_status])
                ->andFilterWhere(['like', 'us.name', $this->user])
                ->andFilterWhere(['like', 'se.name', $this->ordered_by_name]);

        return $dataProvider;
    }

}
