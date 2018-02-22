<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[OrderItems]].
 *
 * @see OrderItems
 */
class OrderItemsQuery extends CommonQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
    
    
     public function orderlist($order) {
        return $this->andWhere(["{$this->tblName}.order_id" => $order]);
    }

    /**
     * @inheritdoc
     * @return OrderItems[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderItems|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
