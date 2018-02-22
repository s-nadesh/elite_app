<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Logins]].
 *
 * @see Logins
 */
class LoginsQuery extends CommonQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Logins[]|array
     */
     public function user($username) {
        
        return $this->andWhere(["{$this->tblName}.username" => $username]);
    }
    public function userid($userid) {
        
        return $this->andWhere(["{$this->tblName}.user_id" => $userid]);
    }
    
    
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
