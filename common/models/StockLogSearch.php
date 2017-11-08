<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StockLog;

/**
 * StockLogSearch represents the model behind the search form about `common\models\StockLog`.
 */
class StockLogSearch extends StockLog {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['stocklog_id', 'product_id', 'adjust_datetime', 'adjust_from', 'adjust_to', 'adjust_quantity', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
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
        $query = StockLog::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if ($params['id']) {
            $query->andWhere(['product_id' => $params['id']]);
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'stocklog_id' => $this->stocklog_id,
            'product_id' => $this->product_id,
            'adjust_datetime' => $this->adjust_datetime,
            'adjust_from' => $this->adjust_from,
            'adjust_to' => $this->adjust_to,
            'adjust_quantity' => $this->adjust_quantity,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
        ]);

        return $dataProvider;
    }

}
