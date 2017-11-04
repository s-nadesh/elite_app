<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_track`.
 * Has foreign keys to the tables:
 *
 * - `order_status`
 * - `order`
 */
class m171102_092646_create_order_track_table extends Migration
{
    /**
     * @inheritdoc
     */
    const ORDER_TRACK_TABLE = '{{%order_track}}';
    const ORDER_STATUS_TABLE = '{{%order_status}}';
    const ORDERS_TABLE = '{{%orders}}';
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(self::ORDER_TRACK_TABLE, [
            'order_track_id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'order_status_id' => $this->integer()->notNull(),
            'value' => $this->text()->Null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'deleted_at' => $this->integer()->defaultValue(0)
        ], $tableOptions);

        // creates index for column `order_status_id`
        $this->createIndex(
            'idx-order_track-order_status_id',
           self::ORDER_TRACK_TABLE,
            'order_status_id'
        );

        // add foreign key for table `order_status`
        $this->addForeignKey(
            'fk-order_track-order_status_id',
            self::ORDER_TRACK_TABLE,
            'order_status_id',
            self::ORDER_STATUS_TABLE,
            'order_status_id'
        );

        // creates index for column `order_id`
        $this->createIndex(
            'idx-order_track-order_id',
            self::ORDER_TRACK_TABLE,
            'order_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
            'fk-order_track-order_id',
            self::ORDER_TRACK_TABLE,
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
        // drops foreign key for table `order_status`
        $this->dropForeignKey(
            'fk-order_track-order_status_id',
            self::ORDER_TRACK_TABLE
        );

        // drops index for column `order_status_id`
        $this->dropIndex(
            'idx-order_track-order_status_id',
            self::ORDER_TRACK_TABLE
        );

        // drops foreign key for table `order`
        $this->dropForeignKey(
            'fk-order_track-order_id',
            self::ORDER_TRACK_TABLE
        );

        // drops index for column `order_id`
        $this->dropIndex(
            'idx-order_track-order_id',
           self::ORDER_TRACK_TABLE
        );

        $this->dropTable(self::ORDER_TRACK_TABLE);
    }
}
