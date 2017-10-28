<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property integer $product_id
 * @property integer $category_id
 * @property integer $subcat_id
 * @property string $product_name
 * @property integer $min_reorder
 * @property integer $stock
 * @property string $price_per_unit
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Categories $category
 * @property SubCategories $subcat
 */
class Products extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function tableName() {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['category_id', 'subcat_id', 'product_name', 'stock', 'price_per_unit'], 'required'],
                [['category_id', 'subcat_id', 'min_reorder', 'stock', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['price_per_unit'], 'number'],
                [['product_name'], 'string', 'max' => 64],
                [['product_name'], 'unique', 'targetAttribute' => ['category_id', 'subcat_id', 'product_name'], 'message' => 'The combination of Category ID, Subcat ID and Product Name has already been taken.'],
                [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
                [['subcat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubCategories::className(), 'targetAttribute' => ['subcat_id' => 'subcat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_id' => 'Product ID',
            'category_id' => 'Category Type',
            'subcat_id' => 'Sub Category Type',
            'product_name' => 'Product Name',
            'min_reorder' => 'Min Reorder',
            'stock' => 'Stock',
            'price_per_unit' => 'Price Per Unit',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubcat() {
        return $this->hasOne(SubCategories::className(), ['subcat_id' => 'subcat_id']);
    }

    /**
     * @inheritdoc
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProductsQuery(get_called_class());
    }

}
