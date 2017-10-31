<?php

use yii\db\Migration;

/**
 * Handles the creation of table `el_order_items`.
 * Has foreign keys to the tables:
 *
 * - `subcat`
 * - `order`
 * - `category`
 * - `product`
 */
class m171030_121917_create_order_items_table extends Migration
{
    /**
     * @inheritdoc
     */
    const ORDER_ITEMS_TABLE = '{{%order_items}}';
    const PRODUCTS_TABLE = '{{%products}}';
    const SUB_CATEGORIES_TABLE = '{{%sub_categories}}';
    const CATEGORIES_TABLE = '{{%categories}}';
    const ORDERS_TABLE = '{{%orders}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }  
        
        $this->createTable(self::ORDER_ITEMS_TABLE, [
            'item_id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'subcat_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'category_name' => $this->string(20)->notNull(),
            'subcat_name' => $this->string(20)->notNull(),
            'product_name' => $this->string(20)->notNull(),
            'quantity' =>$this->integer()->Null(),
            'price' => $this->decimal(10, 2)->notNull(),
            'total' => $this->decimal(10, 2)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);

        // creates index for column `subcat_id`
        $this->createIndex(
            'idx-order_items-subcat_id',
           self::ORDER_ITEMS_TABLE,
            'subcat_id'
        );

        // add foreign key for table   `el_sub_categories`
        $this->addForeignKey(
            'fk-order_items-subcat_id',
           self::ORDER_ITEMS_TABLE,
            'subcat_id',
             self::SUB_CATEGORIES_TABLE,
            'subcat_id'
        );

        // creates index for column `order_id`
        $this->createIndex(
            'idx-order_items-order_id',
             self::ORDER_ITEMS_TABLE,
            'order_id'
        );

        // add foreign key for table  'el_orders'
        $this->addForeignKey(
            'fk-order_items-order_id',
            self::ORDER_ITEMS_TABLE,
            'order_id',
            self::ORDERS_TABLE,
            'order_id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            'idx-order_items-category_id',
           self::ORDER_ITEMS_TABLE,
            'category_id'
        );

        // add foreign key for table  'el_categories'
        $this->addForeignKey(
            'fk-order_items-category_id',
            self::ORDER_ITEMS_TABLE,
            'category_id',
             self::CATEGORIES_TABLE,
            'category_id'
        );

        // creates index for column `product_id`
        $this->createIndex(
            'idx-order_items-product_id',
             self::ORDER_ITEMS_TABLE,
            'product_id'
        );

        // add foreign key for table  'el_products',
        $this->addForeignKey(
            'fk-order_items-product_id',
            self::ORDER_ITEMS_TABLE,
            'product_id',
             self::PRODUCTS_TABLE,
            'product_id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `subcat`
        $this->dropForeignKey(
            'fk-order_items-subcat_id',
           self::ORDER_ITEMS_TABLE
        );

        // drops index for column `subcat_id`
        $this->dropIndex(
            'idx-order_items-subcat_id',
           self::ORDER_ITEMS_TABLE
        );

        // drops foreign key for table `order`
        $this->dropForeignKey(
            'fk-order_items-order_id',
           self::ORDER_ITEMS_TABLE
        );

        // drops index for column `order_id`
        $this->dropIndex(
            'idx-order_items-order_id',
            self::ORDER_ITEMS_TABLE
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-order_items-category_id',
           self::ORDER_ITEMS_TABLE
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-order_items-category_id',
            self::ORDER_ITEMS_TABLE
        );

        // drops foreign key for table `product`
        $this->dropForeignKey(
            'fk-order_items-product_id',
           self::ORDER_ITEMS_TABLE
        );

        // drops index for column `product_id`
        $this->dropIndex(
            'idx-order_items-product_id',
           self::ORDER_ITEMS_TABLE
        );

        $this->dropTable(self::ORDER_ITEMS_TABLE);
    }
}
