<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 * Has foreign keys to the tables:
 *
 * - `el_user_types`
 */
class m171019_131329_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    const USERS_TABLE = '{{%users}}';
    const USER_TYPES_TABLE = '{{%user_types}}';
    
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable(self::USERS_TABLE, [
            'user_id' => $this->primaryKey(),
            'user_type_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'address' => $this->text(),
            'mobile_no' => $this->string(20),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer()
        ], $tableOptions);

        // creates index for column `user_type_id`
        $this->createIndex(
            'idx-users-user_type_id',
            self::USERS_TABLE,
            'user_type_id'
        );

        // add foreign key for table `el_user_types`
        $this->addForeignKey(
            'fk-users-user_type_id',
            self::USERS_TABLE,
            'user_type_id',
            self::USER_TYPES_TABLE,
            'user_type_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `el_user_types`
        $this->dropForeignKey(
            'fk-users-user_type_id',
            self::USERS_TABLE
        );

        // drops index for column `user_type_id`
        $this->dropIndex(
            'idx-users-user_type_id',
            self::USERS_TABLE
        );

        $this->dropTable(self::USERS_TABLE);
    }
}
