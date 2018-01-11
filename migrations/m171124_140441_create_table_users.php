<?php

use yii\db\Migration;
use app\models\User;

class m171124_140441_create_table_users extends Migration
{
    public function safeUp()
    {
        $this->createTable(User::tableName(), [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->unique(),
            'email' => $this->string(256)->notNull()->unique(),
            'password' => $this->text()->notNull(),
            'role' => $this->integer()->notNull(),
            'authkey' => $this->text()->notNull(),
            'access_token' => $this->text()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(User::tableName());
        // echo 'not available to drop users table' . PHP_EOL;
        // return false;
    }
}
