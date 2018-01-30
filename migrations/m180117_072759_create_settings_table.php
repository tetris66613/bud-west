<?php

use yii\db\Migration;
use app\models\Settings;

/**
 * Handles the creation of table `settings`.
 */
class m180117_072759_create_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = '';
        switch ($this->db->driverName) {
            case 'mysql':
                $tableOptions = 'ENGINE=InnoDB';
                break;
        }

        $this->createTable(Settings::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string(80)->notNull()->unique(),
            'value' => $this->text()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Settings::tableName());
    }
}
