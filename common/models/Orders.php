<?php

namespace common\models;

use yii\db\ActiveQuery;

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
class Orders extends RActiveRecord {

    const OR_PAYMENT_P = 'P';
    const OR_PAYMENT_PC = 'PC';
    const OR_PAYMENT_C = 'C';

    public $dispatch_track_id;
    public $dispatch_courier_comapny;
    public $dispatch_comment;
    public $dispatch_date;
    public $deliver_to;
    public $deliver_phone;
    public $deliver_address;
    public $deliver_date;
    public $cancel_comment;
    public $cancel_date;
    public $change_status = false;
    public $pending_amount;

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
            [['user_id', 'invoice_no', 'order_status_id', 'ordered_by', 'items_total_amount', 'total_amount'], 'required'],
            [['invoice_date', 'dispatch_track_id', 'dispatch_courier_comapny', 'dispatch_comment', 'dispatch_date', 'deliver_to', 'deliver_phone', 'deliver_address', 'deliver_date', 'cancel_comment', 'cancel_date', 'pending_amount'], 'safe'],
            [['user_id', 'order_status_id', 'ordered_by', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['items_total_amount', 'tax_percentage', 'tax_amount', 'total_amount'], 'number'],
            [['payment_status'], 'string'],
//            ['end_date', //ajax is working
//                'compare',
//                'compareAttribute' => 'start_date',
//                'operator' => '>=',
//                'skipOnEmpty' => true,
//                'message' => '{attribute} must be greater or equal to "{compareValue}".'
//            ],
//                [['dispatch_track_id', 'dispatch_courier_comapny', 'dispatch_comment'], 'required', 'on' => 'createadmin', 'when' => function ($model) {
//                    return ($model->order_status_id == '4');
//                }, 'whenClient' => "function (attribute, value) {
//                return ($('#orders-order_status_id').val() =='4');
//            }"],
//                [['deliver_to', 'deliver_phone', 'deliver_address'], 'required', 'on' => 'createadmin', 'when' => function ($model) {
//                    return ($model->order_status_id == '5');
//                }, 'whenClient' => "function (attribute, value) {
//                return ($('#orders-order_status_id').val() =='5');
//            }"],
//                [['cancel_comment'], 'required', 'on' => 'createadmin', 'when' => function ($model) {
//                    return ($model->order_status_id == '6');
//                }, 'whenClient' => "function (attribute, value) {
//                return ($('#orders-order_status_id').val() =='6');
//            }"],
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
            'user_id' => 'User',
            'order_status_id' => 'Order Status',
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
     * @return ActiveQuery
     */
    public function getOrderBillings() {
        return $this->hasMany(OrderBillings::className(), ['order_id' => 'order_id']);
    }

    public function getOrderBillingsSum() {
        return $this->hasMany(OrderBillings::className(), ['order_id' => 'order_id'])->sum('paid_amount');
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderItems() {
        return $this->hasMany(OrderItems::className(), ['order_id' => 'order_id']);
    }

    public function getOrderTrack() {
        return $this->hasMany(OrderTrack::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderStatus() {
        return $this->hasOne(OrderStatus::className(), ['order_status_id' => 'order_status_id']);
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

    public static function dateformat($cdate) {
        return date("Y-m-d", strtotime($cdate));
    }

    public function beforeSave($insert) {
        if ($insert) {
            $this->invoice_no = InternalCodes::generateInternalCode('O', 'common\models\Orders', 'invoice_no');
            $this->invoice_date = date("Y-m-d H:m:i");
            $this->order_status_id = OrderStatus::OR_NEW;
            $this->total_amount = $this->items_total_amount; // Tax calculation is not done. 
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            InternalCodes::increaseInternalCode("O");
        }

        if ($this->change_status) {
            $this->insertOrderTrack();
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function insertOrderTrack() {
        $data = [];
        if ($this->order_status_id == OrderStatus::OR_DISPATCHED) {
            $data['dispatch_track_id'] = $this->dispatch_track_id;
            $data['dispatch_courier_comapny'] = $this->dispatch_courier_comapny;
            $data['dispatch_comment'] = $this->dispatch_comment;
        } else if ($this->order_status_id == OrderStatus::OR_DELEVERED) {
            $data['deliver_to'] = $this->deliver_to;
            $data['deliver_phone'] = $this->deliver_phone;
            $data['deliver_address'] = $this->deliver_address;
            $data['deliver_date'] = $this->deliver_date;
        } else if ($this->order_status_id == OrderStatus::OR_CANCELED) {
            $data['cancel_date'] = $this->cancel_date;
            $data['cancel_comment'] = $this->cancel_comment;
            
        }

        $order_track = new OrderTrack();
        $order_track->order_id = $this->order_id;
        $order_track->order_status_id = $this->order_status_id;
        if (!empty($data))
            $order_track->value = json_encode($data, true);
        $order_track->save(false);
    }

    public static function calcItemsTotal($order_items) {
        $items_total_amount = 0;
        if (!empty($order_items)) {
            foreach ($order_items as $value) {
                $items_total_amount += $value['total'];
            }
        }
        return $items_total_amount;
    }

}
