<?php

use yii\db\Migration;

/**
 * Handles adding update_rate to table `user_types`.
 */
class m180529_060431_add_update_rate_column_to_user_types_table extends Migration {

    /**
     * {@inheritdoc}
     */
    const USER_TYPES_TABLE = '{{%user_types}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn(self::USER_TYPES_TABLE, 'update_rate', $this->smallInteger()->notNull()->defaultValue(0)->after('reorder_notify'));
    }

    /**
     * {@inheritdoc}
     */
    public function down() {
        $this->dropColumn(self::USER_TYPES_TABLE, 'update_rate');
    }

}
