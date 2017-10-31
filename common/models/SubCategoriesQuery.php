<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SubCategories]].
 *
 * @see SubCategories
 */
class SubCategoriesQuery extends CommonQuery
{
    public function category($category_id) {
        return $this->andWhere(["{$this->tblName}.category_id" => $category_id]);
    }

    /**
     * @inheritdoc
     * @return SubCategories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubCategories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
