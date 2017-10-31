<?php

use yii\db\Migration;

/**
 * Handles the creation of table `internal_codes`.
 */
class m171031_100420_create_internal_codes_table extends Migration {

    const INTERNAL_CODES_TABLE = '{{%internal_codes}}';

    /**
     * @inheritdoc
     */
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::INTERNAL_CODES_TABLE, [
            'internal_code_id' => $this->primaryKey(),
            'code_type' => $this->string(2)->notNull()->unique(),
            'code_prefix' => $this->string(10)->notNull(),
            'code' => $this->integer()->notNull(),
            'code_padding' => $this->smallInteger()->notNull()->defaultValue(7),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);
        
        $this->insert(self::INTERNAL_CODES_TABLE, ['code_type' => 'O', 'code_prefix' => 'OR', 'code' => '1']);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        echo "m171031_100420_create_internal_codes_table cannot be reverted.\n";
        return false;
    }

}
