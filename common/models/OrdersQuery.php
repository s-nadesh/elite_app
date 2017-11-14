<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Orders]].
 *
 * @see Orders
 */
class OrdersQuery extends CommonQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/
 public function order($user_id,$orderedby) {
           if (strpos($user_id, ',') !== false) {
            $user_id = explode(',', $user_id);
        }
        return $this->andWhere(["{$this->tblName}.user_id" => $user_id ,"{$this->tblName}.ordered_by" => $orderedby]);
    }
    
    public function orderlist($orderedby) {
        return $this->andWhere(["{$this->tblName}.ordered_by" => $orderedby]);
    }
    public function orders($orderid) {
        return $this->andWhere(["{$this->tblName}.order_id" => $orderid]);
    }
    /**
     * @inheritdoc
     * @return Orders[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Orders|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
