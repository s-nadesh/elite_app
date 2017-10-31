<?php

use yii\db\Migration;

/**
 * Handles the creation of table `carts`.
 * Has foreign keys to the tables:
 *
 * - `el_users`
 * - `el_products`
 */
class m171031_105916_create_carts_table extends Migration {

    const CARTS_TABLE = '{{%carts}}';
    const USERS_TABLE = '{{%users}}';
    const PRODUCTS_TABLE = '{{%products}}';

    /**
     * @inheritdoc
     */
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::CARTS_TABLE, [
            'cart_id' => $this->primaryKey(),
            'sessionid' => $this->string(255)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'ordered_by' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'qty' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);

        // creates index for column `ordered_by`
        $this->createIndex(
                'idx-carts-ordered_by', self::CARTS_TABLE, 'ordered_by'
        );

        // add foreign key for table `el_users`
        $this->addForeignKey(
                'fk-carts-ordered_by', self::CARTS_TABLE, 'ordered_by', self::USERS_TABLE, 'user_id', 'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
                'idx-carts-user_id', self::CARTS_TABLE, 'user_id'
        );

        // add foreign key for table `el_users`
        $this->addForeignKey(
                'fk-carts-user_id', self::CARTS_TABLE, 'user_id', self::USERS_TABLE, 'user_id', 'CASCADE'
        );

        // creates index for column `product_id`
        $this->createIndex(
                'idx-carts-product_id', self::CARTS_TABLE, 'product_id'
        );

        // add foreign key for table `el_products`
        $this->addForeignKey(
                'fk-carts-product_id', self::CARTS_TABLE, 'product_id', self::PRODUCTS_TABLE, 'product_id', 'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        // drops foreign key for table `el_users`
        $this->dropForeignKey(
                'fk-carts-ordered_by', self::CARTS_TABLE
        );

        // drops index for column `ordered_by`
        $this->dropIndex(
                'idx-carts-ordered_by', self::CARTS_TABLE
        );

        // drops foreign key for table `el_users`
        $this->dropForeignKey(
                'fk-carts-user_id', self::CARTS_TABLE
        );

        // drops index for column `user_id`
        $this->dropIndex(
                'idx-carts-user_id', self::CARTS_TABLE
        );

        // drops foreign key for table `el_products`
        $this->dropForeignKey(
                'fk-carts-product_id', self::CARTS_TABLE
        );

        // drops index for column `product_id`
        $this->dropIndex(
                'idx-carts-product_id', self::CARTS_TABLE
        );

        $this->dropTable(self::CARTS_TABLE);
    }

}
