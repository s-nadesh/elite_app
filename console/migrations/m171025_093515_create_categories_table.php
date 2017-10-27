<?php

use yii\db\Migration;

/**
 * Handles the creation of table `categories`.
 */
class m171025_093515_create_categories_table extends Migration {

    /**
     * @inheritdoc
     */
    const CATEGORIES_TABLE = '{{%categories}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::CATEGORIES_TABLE, [
            'category_id' => $this->primaryKey(),
            'category_name' => $this->string(20)->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer()
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable(self::CATEGORIES_TABLE);
    }

}
