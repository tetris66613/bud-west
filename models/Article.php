<?php

namespace app\models;

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
        ];
    }

    public function gridData()
    {
        return new ActiveDataProvider([
            'query' => self::find()->select(['id', 'title']),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }
}
