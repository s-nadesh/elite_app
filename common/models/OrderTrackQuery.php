<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[OrderTrack]].
 *
 * @see OrderTrack
 */
class OrderTrackQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OrderTrack[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderTrack|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}