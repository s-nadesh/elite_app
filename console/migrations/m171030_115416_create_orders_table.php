<?php

use yii\db\Migration;

/**
 * Handles the creation of table `el_orders`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m171030_115416_create_orders_table extends Migration
{
    /**
     * @inheritdoc
     */
    const ORDER_STATUS_TABLE = '{{%order_status}}';
    const ORDERS_TABLE = '{{%orders}}';
    const USERS_TABLE = '{{%users}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::ORDERS_TABLE, [
            'order_id' => $this->primaryKey(),
            'invoice_no' => $this->string(50)->unique(),
            'invoice_date' => $this->date()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'order_status_id' => $this->integer()->notNull(),
            'ordered_by' => $this->integer()->notNull(),
            'items_total_amount' => $this->decimal(10, 2)->notNull(),
            'tax_percentage' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'tax_amount' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'total_amount' => $this->decimal(10, 2)->notNull(),
            'payment_status' => "ENUM('P','C','PC')",
            'signature' => $this->string(300)->Null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-orders-user_id',
           self::ORDERS_TABLE,
            'user_id'
        );

        // add foreign key for table  'el_users'
        $this->addForeignKey(
            'fk-orders-user_id',
           self::ORDERS_TABLE,
            'user_id',
           self::USERS_TABLE,
            'user_id',
            'CASCADE'
        );
        
         $this->createIndex(
            'idx-orders-ordered_by',
           self::ORDERS_TABLE,
            'ordered_by'
        );

        // add foreign key for table  'el_users'
        $this->addForeignKey(
            'fk-orders-ordered_by',
           self::ORDERS_TABLE,
            'ordered_by',
           self::USERS_TABLE,
            'user_id',
            'CASCADE'
        );
        
         $this->createIndex(
            'idx-orders-order_status_id',
           self::ORDERS_TABLE,
            'order_status_id'
        );

        // add foreign key for table  'el_users'
        $this->addForeignKey(
            'fk-orders-order_status_id',
           self::ORDERS_TABLE,
            'order_status_id',
           self::ORDER_STATUS_TABLE,
            'order_status_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-orders-user_id',
            self::ORDERS_TABLE
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-orders-user_id',
            self::ORDERS_TABLE
        );

         $this->dropForeignKey(
            'fk-orders-ordered_by',
            self::ORDERS_TABLE
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-orders-ordered_by',
            self::ORDERS_TABLE
        );
        
         $this->dropForeignKey(
            'fk-orders-order_status_id',
            self::ORDERS_TABLE
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-orders-order_status_id',
            self::ORDERS_TABLE
        );

        $this->dropTable(self::ORDERS_TABLE);
    }
}
