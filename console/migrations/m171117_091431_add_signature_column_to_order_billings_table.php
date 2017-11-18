<?php

use yii\db\Migration;

/**
 * Handles adding signature to table `order_billings`.
 */
class m171117_091431_add_signature_column_to_order_billings_table extends Migration
{
    const ORDER_BILLINGS_TABLE = '{{%order_billings}}';
    /**
     * @inheritdoc
     */
    public function up()
    {
          $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn(self::ORDER_BILLINGS_TABLE, 'signature',  $this->string(300)->Null()->after('paid_amount'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn(self::ORDER_BILLINGS_TABLE, 'signature');
    }
}
