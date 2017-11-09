<?php

namespace common\models;

/**
 * This is the model class for table "{{%internal_codes}}".
 *
 * @property integer $internal_code_id
 * @property string $code_type
 * @property string $code_prefix
 * @property integer $code
 * @property integer $code_padding
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_at
 */
class InternalCodes extends RActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%internal_codes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['code_type', 'code_prefix', 'code'], 'required'],
                [['code', 'code_padding', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
                [['code_type'], 'string', 'max' => 2],
                [['code_prefix'], 'string', 'max' => 10],
                [['code_type'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'internal_code_id' => 'Internal Code ID',
            'code_type' => 'Code Type',
            'code_prefix' => 'Code Prefix',
            'code' => 'Code',
            'code_padding' => 'Code Padding',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @inheritdoc
     * @return InternalCodesQuery the active query used by this AR class.
     */
    public static function find() {
        return new InternalCodesQuery(get_called_class());
    }
    
    public function getFullcode() {
        $prefix = $this->code_prefix;
        $int_code = str_pad($this->code, $this->code_padding, '0', STR_PAD_LEFT);

        return "{$prefix}{$int_code}";
    }
    
    public static function increaseInternalCode($code_type) {
        $code = self::find()->codeType($code_type)->one();
        if ($code) {
            $code->code = $code->code + 1;
            $code->save(false);
        }
    }

    public static function generateInternalCode($code_type, $model, $column) {
        $internal_code = self::find()->codeType($code_type)->one();
        $code = $internal_code->Fullcode;
        do {
            $exists = $model::find()->andWhere([$column => $code])->one();

            if (!empty($exists)) {
                $old_code = $code;
                self::increaseInternalCode($code_type);
                $code = self::find()->codeType($code_type)->one()->Fullcode;
            } else {
                break;
            }
        } while ($old_code != $code);
        return $code;
    }

}
