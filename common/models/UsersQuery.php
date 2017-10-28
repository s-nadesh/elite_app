<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Users]].
 *
 * @see Users
 */
class UsersQuery extends CommonQuery {

    public function userType($user_type_id) {
        return $this->andWhere(["{$this->tblName}.user_type_id" => $user_type_id]);
    }

    /**
     * @inheritdoc
     * @return Users[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Users|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

}
