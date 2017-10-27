<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%sub_categories}}".
 *
 * @property integer $subcat_id
 * @property integer $category_id
 * @property string $subcat_name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property Categories $category
 */
class SubCategories extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function tableName() {
        return '{{%sub_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['category_id', 'subcat_name'], 'required'],
                [['category_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['subcat_name'], 'string', 'max' => 20],
                [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'subcat_id' => 'Subcat ID',
            'category_id' => 'Category Type',
            'subcat_name' => 'Sub Category Name',
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
    public function getCategory() {
        return $this->hasOne(Categories::className(), ['category_id' => 'category_id']);
    }

    /**
     * @inheritdoc
     * @return SubCategoriesQuery the active query used by this AR class.
     */
    public static function find() {
        return new SubCategoriesQuery(get_called_class());
    }

    public function afterFind() {
        // convert to display format
        $this->created_at = date('Y-m-d H:i:s');

        parent::afterFind();
    }

}
