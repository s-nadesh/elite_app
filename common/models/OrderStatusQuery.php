<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[OrderStatus]].
 *
 * @see OrderStatus
 */
class OrderStatusQuery extends CommonQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OrderStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
    public static function getUnwantedstatus($current_status) {
        $unwanted = [];
        if ($current_status) {
            $unwanted[OrderStatus::OR_COMPLETED] = OrderStatus::OR_COMPLETED;
            
            if($current_status == OrderStatus::OR_DISPATCHED || $current_status == OrderStatus::OR_DELEVERED){
                $unwanted[OrderStatus::OR_CANCELED] = OrderStatus::OR_CANCELED;
            }
        }
        return $unwanted;
    }
    public function orderstatuslist($order_status_id) {
      if($order_status_id==OrderStatus::OR_NEW){
         return $this->andWhere([ 'OR',"{$this->tblName}.status_position_id!=".OrderStatus::OR_COMPLETED." and {$this->tblName}.order_status_id!=".OrderStatus::OR_DELEVERED." and {$this->tblName}.order_status_id!=".OrderStatus::OR_DISPATCHED ]);
      }elseif($order_status_id==OrderStatus::OR_INPROGRESS){
         return $this->andWhere([ 'OR',"{$this->tblName}.order_status_id!=".OrderStatus::OR_COMPLETED ." and {$this->tblName}.order_status_id!=".OrderStatus::OR_NEW]);
      }elseif($order_status_id==OrderStatus::OR_COMPLETED){
          return  $this->andWhere(["{$this->tblName}.order_status_id"=>OrderStatus::OR_COMPLETED]);
      }elseif($order_status_id==OrderStatus::OR_DISPATCHED){
          return  $this->andWhere([ 'OR',"{$this->tblName}.order_status_id=".OrderStatus::OR_DISPATCHED ." or {$this->tblName}.order_status_id=".OrderStatus::OR_DELEVERED]);
      }elseif($order_status_id==OrderStatus::OR_DELEVERED){
          return  $this->andWhere(["{$this->tblName}.order_status_id"=>OrderStatus::OR_DELEVERED]);
      }elseif($order_status_id==OrderStatus::OR_CANCELED){
          return  $this->andWhere(["{$this->tblName}.order_status_id"=>OrderStatus::OR_CANCELED]);
      }
    }

    /**
     * @inheritdoc
     * @return OrderStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
