<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class UploadImage extends ActiveRecord
{
    public static function tableName()
    {
        return 'upload_images';
    }

    public static function gridData()
    {
        return new ActiveDataProvider([
            'query' => self::find()->select(['title', 'description', 'url']),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'url' => Yii::t('app', 'Url'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
}
