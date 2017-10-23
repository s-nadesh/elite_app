<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[UserTypes]].
 *
 * @see UserTypes
 */
class UserTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return UserTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
