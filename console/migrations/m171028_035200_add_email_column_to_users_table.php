<?php

use yii\db\Migration;

/**
 * Handles adding email to table `users`.
 */
class m171028_035200_add_email_column_to_users_table extends Migration {

    /**
     * @inheritdoc
     */
    const USERS_TABLE = '{{%users}}';
    
    public function up() {
        $this->addColumn(self::USERS_TABLE, 'email', $this->string(64)->notNull()->unique()->after('mobile_no'));
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropColumn(self::USERS_TABLE, 'email');
    }

}
