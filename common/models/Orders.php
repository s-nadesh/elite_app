<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property integer $order_id
 * @property string $invoice_no
 * @property string $invoice_date
 * @property integer $user_id
 * @property integer $order_status_id
 * @property integer $ordered_by
 * @property string $items_total_amount
 * @property string $tax_percentage
 * @property string $tax_amount
 * @property string $total_amount
 * @property string $payment_status
 * @property string $signature
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property OrderBillings[] $orderBillings
 * @property OrderItems[] $orderItems
 * @property OrderStatus $orderStatus
 * @property Users $orderedBy
 * @property Users $user
 */
class Orders extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_id', 'order_status_id', 'ordered_by', 'items_total_amount', 'total_amount'], 'required'],
                [['invoice_date'], 'safe'],
                [['user_id', 'order_status_id', 'ordered_by', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['items_total_amount', 'tax_percentage', 'tax_amount', 'total_amount'], 'number'],
                [['payment_status'], 'string'],
                [['invoice_no'], 'string', 'max' => 50],
                [['signature'], 'string', 'max' => 300],
                [['invoice_no'], 'unique'],
                [['order_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['order_status_id' => 'order_status_id']],
                [['ordered_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['ordered_by' => 'user_id']],
                [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'order_id' => 'Order ID',
            'invoice_no' => 'Invoice No',
            'invoice_date' => 'Invoice Date',
            'user_id' => 'User ID',
            'order_status_id' => 'Order Status ID',
            'ordered_by' => 'Ordered By',
            'items_total_amount' => 'Items Total Amount',
            'tax_percentage' => 'Tax Percentage',
            'tax_amount' => 'Tax Amount',
            'total_amount' => 'Total Amount',
            'payment_status' => 'Payment Status',
            'signature' => 'Signature',
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
    public function getOrderBillings() {
        return $this->hasMany(OrderBillings::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems() {
        return $this->hasMany(OrderItems::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus() {
        return $this->hasOne(OrderStatus::className(), ['order_status_id' => 'order_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderedBy() {
        return $this->hasOne(Users::className(), ['user_id' => 'ordered_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find() {
        return new OrdersQuery(get_called_class());
    }

    public function beforeSave($insert) {
        if ($insert) {
            $this->invoice_no = InternalCodes::generateInternalCode('O', 'common\models\Orders', 'invoice_no');
            $this->invoice_date = date("Y-m-d H:m:i");
            $this->order_status_id = OrderStatus::OR_NEW;
            $this->total_amount = $this->items_total_amount; // Tax calculation is not done. 
            $this->payment_status = 'P';
        }
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            InternalCodes::increaseInternalCode("O");
            //Order Track codes here
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public static function calcItemsTotal($order_items) {
        $items_total_amount = 0;
        if(!empty($order_items)){
            foreach($order_items as $value) {
                $items_total_amount += $value['total'];
            }
        }
        return $items_total_amount;
    }

}
