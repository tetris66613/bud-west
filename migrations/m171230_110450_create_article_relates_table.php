<?php

use yii\db\Migration;
use app\models\ArticleRelate;


/**
 * Handles the creation of table `article_relates`.
 */
class m171230_110450_create_article_relates_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(ArticleRelate::tableName(), [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
            'type_id' => $this->integer()->notNull(),
            'related_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(ArticleRelate::tableName());
    }
}
