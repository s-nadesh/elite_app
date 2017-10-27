<?php

use yii\db\Migration;

/**
 * Handles the creation of table `products`.
 */
class m171027_125511_create_products_table extends Migration {

    /**
     * @inheritdoc
     */
    const PRODUCTS_TABLE = '{{%products}}';
    const SUB_CATEGORIES_TABLE = '{{%sub_categories}}';
    const CATEGORIES_TABLE = '{{%categories}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::PRODUCTS_TABLE, [
            'product_id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'subcat_id' => $this->integer()->notNull(),
            'product_name' => $this->string(64)->notNull(),
            'min_reorder' => $this->integer()->notNull(),
            'stock' => $this->integer()->notNull(),
            'price_per_unit' => $this->decimal(10, 2)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);
        
        $this->createIndex(
            'idx-products-category_id',
            self::PRODUCTS_TABLE,
            'category_id'
        );

        // add foreign key for table `el_categories`
        $this->addForeignKey(
            'fk-products-category_id',
            self::PRODUCTS_TABLE,
            'category_id',
            self::CATEGORIES_TABLE,
            'category_id',
            'CASCADE'
        );
        
        $this->createIndex(
            'idx-products-subcat_id',
            self::PRODUCTS_TABLE,
            'subcat_id'
        );

        // add foreign key for table `el_sub_categories`
        $this->addForeignKey(
            'fk-products-subcat_id',
            self::PRODUCTS_TABLE,
            'subcat_id',
            self::SUB_CATEGORIES_TABLE,
            'subcat_id',
            'CASCADE'
        );
        
    }

    /**
     * @inheritdoc
     */
    public function down() {
        
        $this->dropForeignKey(
            'fk-products-category_id',
            self::PRODUCTS_TABLE
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-products-category_id',
            self::PRODUCTS_TABLE
        );
        
        $this->dropForeignKey(
            'fk-products-subcat_id',
            self::PRODUCTS_TABLE
        );

        // drops index for column `subcat_id`
        $this->dropIndex(
            'idx-products-subcat_id',
            self::PRODUCTS_TABLE
        );
        
        $this->dropTable('products');
    }

}
