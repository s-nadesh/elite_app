<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[OrderBillings]].
 *
 * @see OrderBillings
 */
class OrderBillingsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OrderBillings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderBillings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
