<?php

use yii\db\Migration;
use app\models\Menu;

class m171225_063958_create_table_menus extends Migration
{
    public function safeUp()
    {
        $tableOptions = '';
        switch ($this->db->driverName) {
            case 'mysql':
                $tableOptions = 'ENGINE=InnoDB';
                break;
        }

        $this->createTable(Menu::tableName(), [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull()->defaultValue(Menu::defaultType()),
            'level' => $this->smallInteger()->notNull()->defaultValue(Menu::defaultLevel()),
            'parent' => $this->smallInteger()->notNull()->defaultValue(Menu::defaultParent()),
            'order' => $this->smallInteger()->notNull()->defaultValue(Menu::defaultOrder()),
            'enabled' => $this->smallInteger()->notNull()->defaultValue(Menu::defaultEnabled()),
            'title' => $this->text()->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable(Menu::tableName());
    }
}
