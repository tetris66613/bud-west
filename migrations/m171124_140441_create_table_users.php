<?php

use yii\db\Migration;

class m171124_140441_create_table_users extends Migration
{
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->text()->notNull(),
            'email' => $this->text()->notNull(),
            'role' => $this->integer()->notNull(),
            'authkey' => $this->text()->notNull(),
            'access_token' => $this->text()->notNull()
        ]);
    }

    public function safeDown()
    {
        // $this->dropTable('users');
        echo 'not available to drop users table' . PHP_EOL;
        return false;
    }
}
