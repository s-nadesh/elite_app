<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%categories}}".
 *
 * @property integer $category_id
 * @property string $category_name
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 *
 * @property SubCategories[] $subCategories
 */
class Categories extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
        ];
    }

    public static function tableName() {
        return '{{%categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['category_name'], 'required'],
                [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['category_name'], 'string', 'max' => 20],
                [['category_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'category_id' => 'Category ID',
            'category_name' => 'Category Name',
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
    public function getSubCategories() {
        return $this->hasMany(SubCategories::className(), ['category_id' => 'category_id']);
    }

    /**
     * @inheritdoc
     * @return CategoriesQuery the active query used by this AR class.
     */
    public static function find() {
        return new CategoriesQuery(get_called_class());
    }

    public static function getCategories($map = true) {
        $categories = self::find()
                ->status()
                ->active()
                ->all();
        if ($map) {
            return ArrayHelper::map($categories, 'category_id', 'category_name');
        } else {
            return $categories;
        }
    }

}
