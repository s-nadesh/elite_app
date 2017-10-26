<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[UserTypes]].
 *
 * @see UserTypes
 */
class UserTypesQuery extends CommonQuery
{
    public function visibleSite($visible_site = '1') {
        return $this->andWhere(["{$this->tblName}.visible_site" => $visible_site]);
    }

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
