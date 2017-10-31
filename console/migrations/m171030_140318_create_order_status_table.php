<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_status`.
 */
class m171030_140318_create_order_status_table extends Migration
{
    /**
     * @inheritdoc
     */
    const ORDER_STATUS_TABLE = '{{%order_status}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::ORDER_STATUS_TABLE, [
            'order_status_id' => $this->primaryKey(),
            'status_position_id' =>$this->integer()->notNull()->defaultValue(0),
            'status_name' => $this->string(20)->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
        ], $tableOptions);
       
        $this->insert(self::ORDER_STATUS_TABLE, ['status_name' => 'New Order', 'status_position_id' => 1]);
        $this->insert(self::ORDER_STATUS_TABLE, ['status_name' => 'In Progress', 'status_position_id' => 2]);
        $this->insert(self::ORDER_STATUS_TABLE, ['status_name' => 'Completed', 'status_position_id' => 3]);
        $this->insert(self::ORDER_STATUS_TABLE, ['status_name' => 'Dispatched', 'status_position_id' => 4]);
        $this->insert(self::ORDER_STATUS_TABLE, ['status_name' => 'Delivered', 'status_position_id' =>5]);
        $this->insert(self::ORDER_STATUS_TABLE, ['status_name' => 'Canceled', 'status_position_id' => 6]);
      
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::ORDER_STATUS_TABLE);
    }
}
