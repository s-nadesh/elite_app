<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_status}}".
 *
 * @property integer $order_status_id
 * @property integer $status_position_id
 * @property string $status_name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Orders[] $orders
 */
class OrderStatus extends RActiveRecord {

    const OR_NEW = 1;
    const OR_INPROGRESS = 2;
    const OR_COMPLETED = 3;
    const OR_DISPATCHED = 4;
    const OR_DELEVERED = 5;
    const OR_CANCELED = 6;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%order_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['status_position_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['status_name'], 'required'],
                [['status_name'], 'string', 'max' => 20],
                [['status_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'order_status_id' => 'Order Status ID',
            'status_position_id' => 'Status Position ID',
            'status_name' => 'Status Name',
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
    public function getOrders() {
        return $this->hasMany(Orders::className(), ['order_status_id' => 'order_status_id']);
    }

    /**
     * @inheritdoc
     * @return OrderStatusQuery the active query used by this AR class.
     */
    public static function find() {
        return new OrderStatusQuery(get_called_class());
    }

    public static function getUnwantedstatus($current_status) {
        $unwanted = [];
        if ($current_status) {
            $unwanted[self::OR_COMPLETED] = self::OR_COMPLETED;
            
            if($current_status == self::OR_DISPATCHED || $current_status == self::OR_DELEVERED){
                $unwanted[self::OR_CANCELED] = self::OR_CANCELED;
            }
        }
        return $unwanted;
    }

    public static function prepareOrderStatus($current_status = '') {
        $status = self::find();
        if ($current_status) {
            $status->andWhere(['>=', 'order_status_id', $current_status]);
        }
        $status = $status->all();
        
        $order_status = ArrayHelper::map($status, 'order_status_id', 'status_name');
        $unwanted_status = self::getUnwantedstatus($current_status);
        $result = array_diff_key($order_status, $unwanted_status);
        return $result;
    }

}
