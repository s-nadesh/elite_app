<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Orders]].
 *
 * @see Orders
 */
class OrdersQuery extends CommonQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    public function order($user_id, $orderedby) {
        if (strpos($user_id, ',') !== false) {
            $user_id = explode(',', $user_id);
        }
        return $this->andWhere(["{$this->tblName}.user_id" => $user_id, "{$this->tblName}.ordered_by" => $orderedby]);
    }

    public function orderlist($orderedby) {
        return $this->andWhere(["{$this->tblName}.ordered_by" => $orderedby]);
    }

    public function orders($orderid) {
        return $this->andWhere(["{$this->tblName}.order_id" => $orderid]);
    }

    public function orderstatus($orderstatus) {
        return $this->andWhere(["{$this->tblName}.order_status_id" => $orderstatus]);
    }

    public function searchby($order) {
         $this->joinWith(['user us']);
        return $this->andFilterWhere([
                    'or',
                    ['like', 'total_amount', $order],
                    ['like', 'invoice_no', $order],
                    ['like', 'us.name', $order],
        ]);
    }

    public function getorders($start_date, $end_date) {
        return $this->andWhere('DATE_FORMAT(invoice_date,"%Y-%m-%d") >= "' . Orders::dateformat($start_date) . '" AND DATE_FORMAT(invoice_date,"%Y-%m-%d") <= "' . Orders::dateformat($end_date) . '"');
    }

    /**
     * @inheritdoc
     * @return Orders[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Orders|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

}
