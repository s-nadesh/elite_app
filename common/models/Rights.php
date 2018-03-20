<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "el_rights".
 *
 * @property integer $right_id
 * @property string $rights
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property UserTypesRights[] $userTypesRights
 */
class Rights extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'el_rights';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rights'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['rights'], 'string', 'max' => 55],
            [['rights'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'right_id' => 'Right ID',
            'rights' => 'Rights',
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
    public function getUserTypesRights() {
        return $this->hasMany(UserTypesRights::className(), ['right_id' => 'right_id']);
    }

    public static function getRightList() {
         $users = Rights::find()
                    ->select('rights,right_id')
                    ->all();
            foreach ($users as $user){
                $right[]=$user['rights'];
                $right_id[]=$user['right_id'];
            }
            $rights_ids= array_combine($right_id, $right);
            return $rights_ids;
            
//        return [
//            '1' => 'Make Payment',
//            '2' => 'Change Status',
//            '3' => 'Update Payment',
//            '4' => 'View Payment Log',
//        ];
    }

}
