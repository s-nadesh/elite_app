<?php

use yii\db\Migration;

/**
 * Class m180608_051150_alter_email_column_to_logins_table
 */
class m180608_051150_alter_email_column_to_logins_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    
    const LOGINS_TABLE = '{{%logins}}';
    public function up()
    {
        $this->dropIndex('email', self::LOGINS_TABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->createIndex('email', self::LOGINS_TABLE, 'email', $unique = true );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180608_051150_alter_email_column_to_logins_table cannot be reverted.\n";

        return false;
    }
    */
}
