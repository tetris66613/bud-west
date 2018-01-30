<?php

use yii\db\Migration;
use app\models\User;

class m171124_140441_create_table_users extends Migration
{
    public function safeUp()
    {
        $tableOptions = '';
        switch ($this->db->driverName) {
            case 'mysql':
                // host problem: mysql server issue, not allowing more than 1000 bytes on email
                // column when use utfmb4 character set
                // most popular collate in some time for mysql would be utf8mb4
                // ?that affect for bytes checks in mysql server?
                // for email we need use unique contraint and email in standard
                // can contain more bytes that limits apply
                $email = 'VARCHAR(254) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE';
                $tableOptions = 'ENGINE=InnoDB';
                break;
            default:
                $email = $this->string(254)->notNull()->unique();
        }

        $this->createTable(User::tableName(), [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->unique(),
            'email' => $email,
            'password' => $this->text()->notNull(),
            'role' => $this->integer()->notNull(),
            'authkey' => $this->text()->notNull(),
            'access_token' => $this->text()
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(User::tableName());
        // echo 'not available to drop users table' . PHP_EOL;
        // return false;
    }
}
