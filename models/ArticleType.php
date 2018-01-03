<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\modules\admin\Module;

class ArticleType extends Model
{
    // relates types;
    const RELATE_NO = 0;
    const RELATE_MENU_UNIQUE = 1;
    const RELATE_MENU_LIST = 2;
    const RELATE_MENU_CATEGORY = 3;

    public static function typeItems($excludeNo = true) {
        $items = [
            self::RELATE_NO => Yii::t('app', 'No relate'),
            self::RELATE_MENU_UNIQUE => Yii::t('app', 'Relate menu unique'),
            self::RELATE_MENU_LIST => Yii::t('app', 'Relate menu list'),
            self::RELATE_MENU_CATEGORY => Yii::t('app', 'Relate menu category'),
        ];
        // not implemented yet
        unset($items[self::RELATE_MENU_CATEGORY]);

        if ($excludeNo) {
            unset($items[self::RELATE_NO]);
        }

        return $items;
    }
}
