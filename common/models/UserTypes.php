<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_types}}".
 *
 * @property integer $user_type_id
 * @property string $type_name
 * @property string $type_code
 * @property integer $visible_site
 * @property integer $reorder_notify
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Users[] $users
 */
class UserTypes extends RActiveRecord {

    const AD_USER_TYPE = 1;
    const CU_USER_TYPE = 2;
    const DE_USER_TYPE = 3;
    const SE_USER_TYPE = 4;
    const BE_USER_TYPE = 5;

    public static function tableName() {
        return '{{%user_types}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['type_name', 'type_code'], 'required'],
                [['visible_site', 'reorder_notify', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['type_name'], 'string', 'max' => 64],
                [['type_code'], 'string', 'max' => 3],
                [['type_name'], 'unique'],
                [['type_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_type_id' => 'User Type ID',
            'type_name' => 'Type Name',
            'type_code' => 'Type Code',
            'visible_site' => 'Show in App',
            'reorder_notify' => 'Reorder Notify',
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
    public function getUsers() {
        return $this->hasMany(Users::className(), ['user_type_id' => 'user_type_id']);
    }

    /**
     * @inheritdoc
     * @return UserTypesQuery the active query used by this AR class.
     */
    public static function find() {
        return new UserTypesQuery(get_called_class());
    }

}
