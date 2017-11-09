<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%order_items}}".
 *
 * @property integer $item_id
 * @property integer $order_id
 * @property integer $category_id
 * @property integer $subcat_id
 * @property integer $product_id
 * @property string $category_name
 * @property string $subcat_name
 * @property string $product_name
 * @property integer $quantity
 * @property string $price
 * @property string $total
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Categories $category
 * @property Orders $order
 * @property Products $product
 * @property SubCategories $subcat
 */
class OrderItems extends RActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%order_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['order_id', 'category_id', 'subcat_id', 'product_id', 'category_name', 'subcat_name', 'product_name', 'quantity', 'price', 'total'], 'required'],
                [['order_id', 'category_id', 'subcat_id', 'product_id', 'quantity', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['price', 'total'], 'number'],
                [['category_name', 'subcat_name', 'product_name'], 'string', 'max' => 20],
                [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
                [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'order_id']],
                [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'product_id']],
                [['subcat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubCategories::className(), 'targetAttribute' => ['subcat_id' => 'subcat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'item_id' => 'Item ID',
            'order_id' => 'Order ID',
            'category_id' => 'Category ID',
            'subcat_id' => 'Subcat ID',
            'product_id' => 'Product ID',
            'category_name' => 'Category Name',
            'subcat_name' => 'Subcat Name',
            'product_name' => 'Product Name',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'total' => 'Total',
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
    public function getOrder() {
        return $this->hasOne(Orders::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSubcat() {
        return $this->hasOne(SubCategories::className(), ['subcat_id' => 'subcat_id']);
    }

    /**
     * @inheritdoc
     * @return OrderItemsQuery the active query used by this AR class.
     */
    public static function find() {
        return new OrderItemsQuery(get_called_class());
    }

}
