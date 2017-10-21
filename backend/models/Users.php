<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property integer $user_id
 * @property integer $user_type_id
 * @property string $name
 * @property string $address
 * @property string $mobile_no
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Logins[] $logins
 * @property UserTypes $userType
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_type_id', 'name'], 'required'],
            [['user_type_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['address'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['mobile_no'], 'string', 'max' => 20],
            [['user_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserTypes::className(), 'targetAttribute' => ['user_type_id' => 'user_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_type_id' => 'User Type ID',
            'name' => 'Name',
            'address' => 'Address',
            'mobile_no' => 'Mobile No',
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
    public function getLogins()
    {
        return $this->hasMany(Logins::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserType()
    {
        return $this->hasOne(UserTypes::className(), ['user_type_id' => 'user_type_id']);
    }
}
