<?php

use yii\db\Migration;

/**
 * Handles the creation of table `logins`.
 * Has foreign keys to the tables:
 *
 * - `el_users`
 */
class m171019_133736_create_logins_table extends Migration
{
    /**
     * @inheritdoc
     */
    const LOGINS_TABLE = '{{%logins}}';
    const USERS_TABLE = '{{%users}}';
    
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable(self::LOGINS_TABLE, [
            'login_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer()
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-logins-user_id',
            self::LOGINS_TABLE,
            'user_id'
        );

        // add foreign key for table `el_users`
        $this->addForeignKey(
            'fk-logins-user_id',
            self::LOGINS_TABLE,
            'user_id',
            self::USERS_TABLE,
            'user_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `el_users`
        $this->dropForeignKey(
            'fk-logins-user_id',
            self::LOGINS_TABLE
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-logins-user_id',
            self::LOGINS_TABLE
        );

        $this->dropTable(self::LOGINS_TABLE);
    }
}
