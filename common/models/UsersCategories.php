<?php

namespace common\models;

use yii\db\ActiveQuery;

/**
 * This is the model class for table "el_user_types_rights".
 *
 * @property integer $user_category_id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Users $user
 * @property Categories $category
 */
class UsersCategories extends RActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'el_users_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'category_id'], 'required'],
            [['user_id', 'category_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_category_id' => 'User Category ID',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
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
    public function getUser() {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_id']);
    }

}
