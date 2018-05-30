<?php

namespace common\models;

use yii\db\ActiveQuery;
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
 * @property UsersCategories[] $UsersCategories
 */
class Categories extends RActiveRecord {

    public static function tableName() {
        return '{{%categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['category_name'], 'required'],
                [['cat_logo', 'category_name'], 'safe'],
                [['cat_logo'], 'file', 'extensions' => 'jpeg,jpg,png'],
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
            'cat_logo' => 'Category Logo'
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

    public static function getCategoryList() {
        $categories = Categories::find()
                ->select('category_id,category_name')
                ->all();
        foreach ($categories as $category) {
            $category_name[] = $category['category_name'];
            $category_id[] = $category['category_id'];
        }
        $category_ids = array_combine($category_id, $category_name);
        return $category_ids;
    }

}
