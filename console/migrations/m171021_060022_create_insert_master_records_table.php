<?php

use yii\db\Migration;

/**
 * Handles the creation of table `insert_master_records`.
 */
class m171021_060022_create_insert_master_records_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insert('{{%user_types}}', ['type_name' => 'admin', 'type_code' => 'AD']);
        $this->insert('{{%users}}', ['user_type_id'=>'1','name' => 'admin']);
        $this->insert('{{%logins}}', ['user_id'=>'1','username' => 'admin','auth_key'=>'UrkcrjINfapGyQ08ZDVSALvr93MRfGCc','password_hash'=>'$2y$13$Gj2KhuT3sASdkNCoWPsQDuPPMGg0o05wcrs2hlLYxaYErhV6wUd.K','email'=>'admin@gmail.com']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('insert_master_records');
        $this->delete('{{%user_types}}', ['type_name' => 'admin', 'type_code' => 'AD']);
        $this->delete('{{%users}}', ['name' => 'admin']);
        $this->delete('{{%logins}}', ['user_id'=>'1','username' => 'admin','auth_key'=>'UrkcrjINfapGyQ08ZDVSALvr93MRfGCc','password_hash'=>'$2y$13$Gj2KhuT3sASdkNCoWPsQDuPPMGg0o05wcrs2hlLYxaYErhV6wUd.K','email'=>'admin@gmail.com']);
    }
}
