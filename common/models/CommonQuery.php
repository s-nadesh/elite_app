<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[UserTypes]].
 *
 * @see UserTypes
 */
class CommonQuery extends ActiveQuery
{
    public $tblName;

    public function __construct($modelClass, $config = array()) {
        $this->tblName = $modelClass::tableName();
        
        parent::__construct($modelClass, $config);
    }
    
    public function status($status = '1') {
        if (strpos($status, ',') !== false) {
            $status = explode(',', $status);
        }
        return $this->andWhere(["{$this->tblName}.status" => $status]);
    }
    
    public function active() {
        return $this->andWhere("{$this->tblName}.deleted_at = 0");
    }

    public function deleted() {
        return $this->andWhere("{$this->tblName}.deleted_at > '0'");
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
