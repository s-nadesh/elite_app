<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[InternalCodes]].
 *
 * @see InternalCodes
 */
class InternalCodesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return InternalCodes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return InternalCodes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
