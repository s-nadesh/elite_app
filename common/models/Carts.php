<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%carts}}".
 *
 * @property integer $cart_id
 * @property string $sessionid
 * @property integer $user_id
 * @property integer $ordered_by
 * @property integer $product_id
 * @property integer $qty
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Users $orderedBy
 * @property Products $product
 * @property Users $user
 */
class Carts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%carts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sessionid', 'user_id', 'ordered_by', 'product_id', 'qty'], 'required'],
            [['user_id', 'ordered_by', 'product_id', 'qty', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['sessionid'], 'string', 'max' => 255],
            [['ordered_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['ordered_by' => 'user_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'product_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cart_id' => 'Cart ID',
            'sessionid' => 'Sessionid',
            'user_id' => 'User ID',
            'ordered_by' => 'Ordered By',
            'product_id' => 'Product ID',
            'qty' => 'Qty',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderedBy()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'ordered_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return CartsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CartsQuery(get_called_class());
    }
}
