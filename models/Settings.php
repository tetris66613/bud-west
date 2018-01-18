<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Settings extends ActiveRecord
{
    public static function selectAll()
    {
        static $names = null;
        if ($names === null) {
            $names = ArrayHelper::map(self::find()->asArray()->all(), 'name', 'value');
        }

        return $names;
    }

    public static function findValueByName($name)
    {
        $names = self::selectAll();

        if (isset($names[$name])) return $names[$name];

        $predefinedSettings = self::predefinedSettings();
        if (isset($predefinedSettings[$name])) {
            return $predefinedSettings[$name];
        }

        return null;
    }

    public static function isMaintance()
    {
        $value = self::findValueByName('maintance');
        return ($value === null) ? true : boolval($value);
    }

    public static function predefinedSettings()
    {
        return [
            'maintance' => true,
        ];
    }
}
