<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class Carts extends ActiveRecord {

    public $category_id;
    public $subcat_id;
    public $product_price;
    public $total_amount;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%carts}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_id', 'ordered_by', 'product_id', 'qty', 'category_id', 'subcat_id', 'product_price'], 'required'],
                [['user_id', 'ordered_by', 'product_id', 'qty', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['sessionid'], 'string', 'max' => 255],
                [['ordered_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['ordered_by' => 'user_id']],
                [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'product_id']],
                [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
                [['category_id', 'subcat_id', 'product_price', 'total_amount'], 'safe'],
                ['qty', 'stockCheck']
        ];
    }

    public function stockCheck($attribute, $params) {
        if (!empty($this->qty)) {
            if ($this->qty > $this->product->stock)
                $this->addError($attribute, "Stock is not available");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'cart_id' => 'Cart ID',
            'sessionid' => 'Sessionid',
            'user_id' => 'Customer / Dealer',
            'ordered_by' => 'Sales Executive',
            'product_id' => 'Product',
            'qty' => 'Quantity',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'category_id' => 'Category',
            'subcat_id' => 'Subcategory',
            'product_price' => 'Product Price',
            'total_amount' => 'Total Amount',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderedBy() {
        return $this->hasOne(Users::className(), ['user_id' => 'ordered_by']);
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
    public function getUser() {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return CartsQuery the active query used by this AR class.
     */
    public static function find() {
        return new CartsQuery(get_called_class());
    }

    public static function clearCart() {
        $session = Yii::$app->session;
        if (isset($session['carts'])) {
            self::deleteAll($session['carts']);
            unset($session['carts']);
        }
    }

    public static function cartExists($model) {
        $cart = self::find()
                ->andWhere([
                    'sessionid' => $model->sessionid,
                    'user_id' => $model->user_id,
                    'ordered_by' => $model->ordered_by,
                    'product_id' => $model->product_id,
                ])
                ->one();
        return $cart;
    }

    public static function getTotalAmount($provider) {
        $total = 0;
        foreach ($provider as $model) {
            $total += ($model->product->price_per_unit * $model->qty);
        }
        return '<b>'.$total.'</b>';
    }

}
