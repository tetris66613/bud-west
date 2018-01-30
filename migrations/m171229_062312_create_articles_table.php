<?php

use yii\db\Migration;
use app\models\Article;

/**
 * Handles the creation of table `articles`.
 */
class m171229_062312_create_articles_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $content = '';
        $tableOptions = '';
        switch ($this->db->driverName) {
            case 'mysql':
                $content = 'MEDIUMTEXT NOT NULL';
                $tableOptions = 'ENGINE=InnoDB';
                break;
            default:
                $content = $this->text()->notNull();
        }

        $this->createTable(Article::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'description' => $this->text()->notNull(),
            'content' => $content,
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Article::tableName());
    }
}
