<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "el_user_types_rights".
 *
 * @property integer $type_rights_id
 * @property integer $right_id
 * @property integer $user_type_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Rights $right
 * @property UserTypes $userType
 */
class UserTypesRights extends RActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'el_user_types_rights';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['right_id', 'user_type_id'], 'required'],
            [['right_id', 'user_type_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['right_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rights::className(), 'targetAttribute' => ['right_id' => 'right_id']],
            [['user_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserTypes::className(), 'targetAttribute' => ['user_type_id' => 'user_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'type_rights_id' => 'Type Rights ID',
            'right_id' => 'Right ID',
            'user_type_id' => 'User Type ID',
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
    public function getRight() {
        return $this->hasOne(Rights::className(), ['right_id' => 'right_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserType() {
        return $this->hasOne(UserTypes::className(), ['user_type_id' => 'user_type_id']);
    }

    public static function getResult($r_id, $u_id) {
        $delete_row = self::find()
                ->andWhere([
                    'right_id' => $r_id,
                    'user_type_id' => $u_id,
                ])
                ->one();
        if(!empty($delete_row))
        $delete_row->delete();
    }

    public static function createRights($r_id, $u_id) {
        $ur_model = new UserTypesRights();
        $ur_model->right_id = $r_id;
        $ur_model->user_type_id = $u_id;
        $ur_model->save();
    }

}
