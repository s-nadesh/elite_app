<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Carts]].
 *
 * @see Carts
 */
class CartsQuery extends CommonQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

     public function cart($user_id,$orderedby) {
           if (strpos($user_id, ',') !== false) {
            $user_id = explode(',', $user_id);
        }
        return $this->andWhere(["{$this->tblName}.user_id" => $user_id ,"{$this->tblName}.ordered_by" => $orderedby]);
    }
    public function editcart($cart_id) {
                 return $this->andWhere(["{$this->tblName}.cart_id" => $cart_id]);
    }
    
    
    /**
     * @inheritdoc
     * @return Carts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Carts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
