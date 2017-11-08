<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_billings}}".
 *
 * @property integer $billing_id
 * @property integer $order_id
 * @property string $paid_amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Orders $order
 */
class OrderBillings extends \yii\db\ActiveRecord
{
     const OR_STATUS = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_billings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
     
        return [
            [['order_id','paid_amount'], 'required'],
            [['order_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['paid_amount'], 'number'],
//             ['paid_amount', 'amountCheck'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    
//    public function amountCheck($attribute, $params) {
////           print_r($this->order->total_amount);exit;
//         $pending_amount = OrderBillings::pendingAmount($this->order->total_amount, $this->paid_amount);
//            if ($this->paid_amount > $pending_amount){
//                $this->addError($attribute, "paid amount is greater than pending amount");
//        }
//    }
    
    public function attributeLabels()
    {
        return [
            'billing_id' => 'Billing ID',
            'order_id' => 'Order ID',
            'paid_amount' => 'Paid Amount',
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
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['order_id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @return OrderBillingsQuery the active query used by this AR class.
     */
    public function afterSave($insert, $changedAttributes) {
        $this->checkOrderTotal();
        return parent::afterSave($insert, $changedAttributes);
    }
    public static function find()
    {
        return new OrderBillingsQuery(get_called_class());
    }
         
    public static function paidAmount($id) {
        $paid = self::find()
                ->andWhere([
                    'order_id' => $id,
                    'status' => 1,
                ])
                ->sum('paid_amount');
        return $paid;
    }
     public static function pendingAmount($total,$paid_amount) {
         
       $pending=$total-$paid_amount;
       
        return $pending;
    }
     public function insertOrderBilling($model,$orderbilling_model) {
        $order_billing = new OrderBillings();
        $order_billing->order_id = $model->order_id;
        $order_billing->paid_amount = $orderbilling_model->paid_amount;
        $order_billing->status =  OrderBillings::OR_STATUS;
        $order_billing->created_by =  $model->ordered_by;
        $order_billing->save(false);
     }
     
     public function checkOrderTotal() {
       $paid_amount= OrderBillings::paidAmount($this->order_id);
       $diff = $this->order->total_amount - $paid_amount;
       if($diff == 0 && $this->order->order_status_id == OrderStatus::OR_DELEVERED){
           $this->order->order_status_id=OrderStatus::OR_COMPLETED;
           $this->order->save(false);
       }
    }

}
