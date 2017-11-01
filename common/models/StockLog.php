<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%stock_log}}".
 *
 * @property integer $stocklog_id
 * @property integer $product_id
 * @property integer $adjust_datetime
 * @property integer $adjust_from
 * @property integer $adjust_to
 * @property integer $adjust_quantity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Products $product
 */
class StockLog extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
        ];
    }

    public function getCreateUser() {
        return $this->hasOne(Users::className(), ['user_id' => 'created_by']);
    }

    /**
     * @getCreateUserName
     * 
     */
    public function getCreateUserName() {
        return $this->createUser ? $this->createUser->name : '- no user -';
    }

    public function getUpdateUser() {
        return $this->hasOne(Users::className(), ['user_id' => 'updated_by']);
    }

    /**
     * @getUpdateUserName
     * 
     */
    public function getUpdateUserName() {
        return $this->createUser ? $this->updateUser->name : '- no user -';
    }

    public static function tableName() {
        return '{{%stock_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['product_id', 'adjust_datetime', 'adjust_from', 'adjust_to', 'adjust_quantity'], 'required'],
                [['product_id', 'adjust_datetime', 'adjust_from', 'adjust_to', 'adjust_quantity', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'product_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'stocklog_id' => 'Stocklog ID',
            'product_id' => 'Product Name',
            'adjust_datetime' => 'Adjust Datetime',
            'adjust_from' => 'Adjust From',
            'adjust_to' => 'Current Stock',
            'adjust_quantity' => 'Reorder Quantity',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'createUserName' =>'Created By',
            'updateUserName' => 'Updated By',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Products::className(), ['product_id' => 'product_id']);
    }

    /**
     * @inheritdoc
     * @return StockLogQuery the active query used by this AR class.
     */
    public static function find() {
        return new StockLogQuery(get_called_class());
    }

}
