<?php

namespace common\models;

use common\models\Logins;
use common\models\UsersQuery;
use common\models\UserTypes;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property integer $user_id
 * @property integer $user_type_id
 * @property string $name
 * @property string $address
 * @property string $mobile_no
 * @property string $email
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
class Users extends RActiveRecord {

    public static function tableName() {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_type_id', 'name', 'email'], 'required'],
                [['user_type_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['address'], 'string'],
                [['name'], 'string', 'max' => 255],
                [['mobile_no'], 'string', 'max' => 20],
                [['email'], 'string', 'max' => 64],
                [['email'], 'unique'],
                [['user_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserTypes::className(), 'targetAttribute' => ['user_type_id' => 'user_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_id' => 'User ID',
            'user_type_id' => 'User Type',
            'name' => 'Name',
            'address' => 'Address',
            'mobile_no' => 'Mobile No',
            'email' => 'Email',
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
    public function getLogins() {
        return $this->hasMany(Logins::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserType() {
        return $this->hasOne(UserTypes::className(), ['user_type_id' => 'user_type_id']);
    }

    /**
     * @inheritdoc
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find() {
        return new UsersQuery(get_called_class());
    }

    public static function getUsersbytype($user_type_id, $map = true) {
        $users = self::find()
                ->userType($user_type_id)
                ->status()
                ->active()
                ->all();
        if ($map) {
            return ArrayHelper::map($users, 'user_id', 'name');
        } else {
            return $users;
        }
    }
    public static function userExists($info) {
        $user = self::find()
                ->andWhere([
                    'user_id' => $info->created_by,
                    'status' => 1,
                ])
                ->one();
        return $user;
    }
 public static function getUsername($name) {
    
        $user = self::find()
                ->select('name')
                ->andWhere([
                    'user_id' => $name,
                    'status' => 1,
                ])
                ->one();
        return $user;
    }

}
