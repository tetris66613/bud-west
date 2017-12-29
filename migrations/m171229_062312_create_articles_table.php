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
        $this->createTable(Article::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'content' => $this->text()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Article::tableName());
    }
}
