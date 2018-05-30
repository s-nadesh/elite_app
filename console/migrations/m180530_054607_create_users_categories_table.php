<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users_categories`.
 */
class m180530_054607_create_users_categories_table extends Migration {

    /**
     * {@inheritdoc}
     */
    const USERCATEGORY_TABLE = '{{%users_categories}}';
    const USER_TABLE = '{{%users}}';
    const CATEGORIES_TABLE = '{{%categories}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::USERCATEGORY_TABLE, [
            'user_category_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);

        // creates index for column `user_type_id`
        $this->createIndex(
                'idx-users_categories-user_id', self::USERCATEGORY_TABLE, 'user_id'
        );

        // add foreign key for table `el_user_types`
        $this->addForeignKey(
                'fk-users_categories-user_id', self::USERCATEGORY_TABLE, 'user_id', self::USER_TABLE, 'user_id', 'CASCADE'
        );

        // creates index for column `user_type_id`
        $this->createIndex(
                'idx-users_categories-category_id', self::USERCATEGORY_TABLE, 'category_id'
        );

        // add foreign key for table `el_user_types`
        $this->addForeignKey(
                'fk-users_categories-category_id', self::USERCATEGORY_TABLE, 'category_id', self::CATEGORIES_TABLE, 'category_id', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('users_categories');
    }

}
