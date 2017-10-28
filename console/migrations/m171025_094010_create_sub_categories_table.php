<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sub_categories`.
 */
class m171025_094010_create_sub_categories_table extends Migration {

    /**
     * @inheritdoc
     */
    const SUB_CATEGORIES_TABLE = '{{%sub_categories}}';
    const CATEGORIES_TABLE = '{{%categories}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::SUB_CATEGORIES_TABLE, [
            'subcat_id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'subcat_name' => $this->string(20)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
                ], $tableOptions);
        
        
        $this->createIndex(
            'idx-sub_categories-category_id',
            self::SUB_CATEGORIES_TABLE,
            'category_id'
        );
        
        //Two columns unique
        $this->createIndex(
                'idx-sub_categories-unique_category_id_subcat_name',
                self::SUB_CATEGORIES_TABLE,
                ['category_id', 'subcat_name'], 
                true
        );

        // add foreign key for table `el_categories`
        $this->addForeignKey(
            'fk-sub_categories-category_id',
            self::SUB_CATEGORIES_TABLE,
            'category_id',
            self::CATEGORIES_TABLE,
            'category_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        
        $this->dropForeignKey(
            'fk-sub_categories-category_id',
            self::SUB_CATEGORIES_TABLE
        );
        
        $this->dropIndex(
            'idx-sub_categories-unique_category_id_subcat_name',
            self::SUB_CATEGORIES_TABLE
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-sub_categories-category_id',
            self::SUB_CATEGORIES_TABLE
        );
        
        $this->dropTable(self::SUB_CATEGORIES_TABLE);
    }

}
