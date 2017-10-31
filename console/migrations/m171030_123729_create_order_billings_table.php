<?php

use yii\db\Migration;

/**
 * Handles the creation of table `el_order_billings`.
 * Has foreign keys to the tables:
 *
 * - `order`
 */
class m171030_123729_create_order_billings_table extends Migration
{
    /**
     * @inheritdoc
     */
    const ORDER_BILLINGS_TABLE = '{{%order_billings}}';
    const ORDERS_TABLE = '{{%orders}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable( self::ORDER_BILLINGS_TABLE, [
            'billing_id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'paid_amount' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
        ], $tableOptions);

        // creates index for column `order_id`
        $this->createIndex(
            'idx-order_billings-order_id',
            self::ORDER_BILLINGS_TABLE,
            'order_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
            'fk-order_billings-order_id',
           self::ORDER_BILLINGS_TABLE,
            'order_id',
           self::ORDERS_TABLE,
            'order_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `order`
        $this->dropForeignKey(
            'fk-order_billings-order_id',
            self::ORDER_BILLINGS_TABLE
        );

        // drops index for column `order_id`
        $this->dropIndex(
            'idx-order_billings-order_id',
          self::ORDER_BILLINGS_TABLE
        );

        $this->dropTable(self::ORDER_BILLINGS_TABLE);
    }
}
