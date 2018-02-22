<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[OrderTrack]].
 *
 * @see OrderTrack
 */
class OrderTrackQuery extends CommonQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OrderTrack[]|array
     */
    
    public function cancel_order_track($orderid,$order_status) {
        
 return $this->andWhere(["{$this->tblName}.order_id" => $orderid, "{$this->tblName}.order_status_id" => $order_status]);
 
 }
 public function ordertrack($order_track_id) {
        
 return $this->andWhere(["{$this->tblName}.order_track_id" => $order_track_id]);
 
 }
    
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderTrack|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

}
