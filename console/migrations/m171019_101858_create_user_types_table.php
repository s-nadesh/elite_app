<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_types`.
 */
class m171019_101858_create_user_types_table extends Migration {

    /**
     * @inheritdoc
     */
    const USER_TYPES_TABLE = '{{%user_types}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::USER_TYPES_TABLE, [
            'user_type_id' => $this->primaryKey(),
            'type_name' => $this->string(64)->notNull()->unique(),
            'type_code' => $this->string(3)->notNull()->unique(),
            'visible_site' => $this->smallInteger()->notNull()->defaultValue(0),
            'reorder_notify' => $this->smallInteger()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable(self::USER_TYPES_TABLE);
    }

}
