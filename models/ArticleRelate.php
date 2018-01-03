<?php

namespace app\models;

use yii\db\ActiveRecord;

class ArticleRelate extends ActiveRecord
{
    public static function tableName()
    {
        return 'article_relates';
    }
}
