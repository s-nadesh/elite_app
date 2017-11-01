<?php

use yii\db\Migration;

/**
 * Handles the creation of table `stock_log`.
 */
class m171031_134742_create_stock_log_table extends Migration {

    /**
     * @inheritdoc
     */
    const STOCK_LOG_TABLE = '{{%stock_log}}';
    const PRODUCTS_TABLE = '{{%products}}';

    public function up() {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::STOCK_LOG_TABLE, [
            'stocklog_id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'adjust_datetime' => $this->integer()->notNull(),
            'adjust_from' => $this->integer()->notNull(),
            'adjust_to' => $this->integer()->notNull(),
            'adjust_quantity' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);
        
        $this->createIndex(
            'idx-stock_log-product_id',
            self::STOCK_LOG_TABLE,
            'product_id'
        );

        // add foreign key for table `el_products`
        $this->addForeignKey(
            'fk-stock_log-product_id',
            self::STOCK_LOG_TABLE,
            'product_id',
            self::PRODUCTS_TABLE,
            'product_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropIndex(
            'idx-stock_log-product_id',
            self::STOCK_LOG_TABLE
        );
        
        $this->dropForeignKey(
            'fk-stock_log-product_id',
            self::STOCK_LOG_TABLE
        );
        
        $this->dropTable('stock_log');
    }

}
