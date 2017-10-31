<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Carts]].
 *
 * @see Carts
 */
class CartsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Carts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Carts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
