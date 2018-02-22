<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

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
class Products extends RActiveRecord {

    public static function tableName() {
        return '{{%products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['category_id', 'product_name', 'stock', 'min_reorder'], 'required'],
            [['product_logo', 'product_name'], 'safe'],
            [['product_logo'], 'file', 'extensions' => 'jpeg,jpg,png'],
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
            'min_reorder' => 'Minimum Reorder Level',
            'stock' => 'Current Stock',
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
     public function getCarts() {
        return $this->hasOne(Carts::className(), ['product_id' => 'product_id']);
    }

    /**
     * @inheritdoc
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProductsQuery(get_called_class());
    }

    public static function getStock($id) {
        $quanty = self::find()
                ->andWhere([
                    'product_id' => $id,
                    'status' => 1,
                ])
                ->one();
        return $quanty;
    }

    public static function getProducts($category_id, $subcat_id, $map = true) {
        $products = self::find()
                ->category($category_id)
                ->subcategory($subcat_id)
                ->status()
                ->active()
                ->all();
        if ($map) {
            return ArrayHelper::map($products, 'product_id', 'product_name');
        } else {
            return $products;
        }
    }

}
