<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Products]].
 *
 * @see Products
 */
class ProductsQuery extends CommonQuery
{
    public function category($category_id) {
        return $this->andWhere(["{$this->tblName}.category_id" => $category_id]);
    }
    
    public function subcategory($subcat_id) {
        return $this->andWhere(["{$this->tblName}.subcat_id" => $subcat_id]);
    }
     public function quantity_check($product_id) {
        return $this->andWhere(["{$this->tblName}.product_id" => $product_id]);
    }
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Products[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Products|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
