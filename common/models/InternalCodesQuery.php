<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[InternalCodes]].
 *
 * @see InternalCodes
 */
class InternalCodesQuery extends CommonQuery
{
    public function codeType($code_type = 'O') {
        return $this->andWhere(["{$this->tblName}.code_type" => $code_type]);
    }

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
