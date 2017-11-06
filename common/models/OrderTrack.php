<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_track}}".
 *
 * @property integer $order_track_id
 * @property integer $order_id
 * @property integer $order_status_id
 * @property string $value
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Orders $order
 * @property OrderStatus $orderStatus
 */
class OrderTrack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_track}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'safe'],
            [[ 'order_status_id'], 'required'],
            [['dispatch_track_id', 'dispatch_courier_comapny', 'dispatch_comment'], 'required', 'on' => 'createadmin', 'when' => function ($model) {
                    return ($model->order_status_id == '4');
                }, 'whenClient' => "function (attribute, value) {
                return ($('#orders-order_status_id').val() =='4');
            }"],
            [['deliver_to', 'deliver_phone', 'deliver_address'], 'required', 'on' => 'createadmin', 'when' => function ($model) {
                    return ($model->order_status_id == '5');
                }, 'whenClient' => "function (attribute, value) {
                return ($('#orders-order_status_id').val() =='5');
            }"],
            [['cancel_comment'], 'required', 'on' => 'createadmin', 'when' => function ($model) {
                    return ($model->order_status_id == '6');
                }, 'whenClient' => "function (attribute, value) {
                return ($('#orders-order_status_id').val() =='6');
            }"],
            [['order_id', 'order_status_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['value'], 'string'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'order_id']],
            [['order_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['order_status_id' => 'order_status_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_track_id' => 'Order Track ID',
            'order_id' => 'Order ID',
            'order_status_id' => 'Order Status ID',
            'value' => 'Value',
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
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['order_status_id' => 'order_status_id']);
    }

    /**
     * @inheritdoc
     * @return OrderTrackQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderTrackQuery(get_called_class());
    }
}