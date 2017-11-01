<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[StockLog]].
 *
 * @see StockLog
 */
class StockLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return StockLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StockLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
