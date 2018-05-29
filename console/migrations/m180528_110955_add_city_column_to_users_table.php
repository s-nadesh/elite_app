<?php

use yii\db\Migration;

/**
 * Handles adding city to table `users`.
 */
class m180528_110955_add_city_column_to_users_table extends Migration {

    /**
     * {@inheritdoc}
     */
    const USERS_TABLE = '{{%users}}';

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn(self::USERS_TABLE, 'city', $this->string(64)->Null()->after('address'));
    }

    /**
     * {@inheritdoc}
     */
    public function down() {
        $this->dropColumn(self::USERS_TABLE, 'city');
    }

}
