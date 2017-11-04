<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Carts;

/**
 * CartSearch represents the model behind the search form about `common\models\Carts`.
 */
class CartSearch extends Carts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cart_id', 'user_id', 'ordered_by', 'product_id', 'qty', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['sessionid'], 'safe'],
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
        $query = Carts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'cart_id' => $this->cart_id,
            'user_id' => $this->user_id,
            'ordered_by' => $this->ordered_by,
            'product_id' => $this->product_id,
            'qty' => $this->qty,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'sessionid', $this->sessionid]);

        return $dataProvider;
    }
}
