<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class Article extends ActiveRecord
{
    public static function tableName()
    {
        return 'articles';
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            ['description', 'default', 'value' => ''],
        ];
    }

    public static function gridData()
    {
        return new ActiveDataProvider([
            'query' => self::find()->select(['id', 'title']),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }

    public function getArticleRelateMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'related_id'])->via('articleRelate');
    }

    public function getArticleRelate()
    {
        return $this->hasOne(ArticleRelate::className(), ['article_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
        ];
    }
}
