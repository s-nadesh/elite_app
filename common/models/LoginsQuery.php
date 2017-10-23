<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Logins]].
 *
 * @see Logins
 */
class LoginsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Logins[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Logins|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
