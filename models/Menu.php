<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    // predefined menus types
    const TYPE_CLIENT_NAVBAR = 1;
    const TYPE_TEST = 2;

    const LEVEL_ROOT = 0;
    const LEVEL_CHILD_1 = 1;

    const ENABLED_FALSE = 0;
    const ENABLED_TRUE = 1;

    // not related to any parent
    const PARENT_NO = 0;

    const DEFAULT_PARENT = 0;
    const DEFAULT_ORDER = 1;

    public static function tableName()
    {
        return 'menus';
    }

    public function getParentRelation()
    {
        return $this->hasOne(self::className(), ['id' => 'parent']);
    }

    public function rules()
    {
        return [
            [['type', 'level', 'parent', 'order', 'enabled', 'title'], 'required'],
        ];
    }

    public static function defaultType()
    {
        return self::TYPE_CLIENT_NAVBAR;
    }

    public static function defaultLevel()
    {
        return self::LEVEL_ROOT;
    }

    public static function defaultParent()
    {
        return self::DEFAULT_PARENT;
    }

    public static function defaultOrder()
    {
        return self::DEFAULT_ORDER;
    }

    public static function defaultEnabled()
    {
        return self::ENABLED_TRUE;
    }

    public static function typeItems()
    {
        return [
            self::TYPE_CLIENT_NAVBAR => Yii::t('app', 'Client navbar'),
            self::TYPE_TEST => Yii::t('app', 'Test type'),
        ];
    }

    public static function levelItems()
    {
        return [
            self::LEVEL_ROOT => Yii::t('app', 'Menu'),
            self::LEVEL_CHILD_1 => Yii::t('app', 'Submenu'),
        ];
    }

    public static function enabledItems()
    {
        return [
            self::ENABLED_FALSE => Yii::t('app', 'Disabled'),
            self::ENABLED_TRUE => Yii::t('app', 'Enabled'),
        ];
    }

    public static function gridData()
    {
        $data = new ActiveDataProvider([
            'query' => self::find()->select(['id', 'type', 'level', 'parent', 'order', 'enabled', 'title'])->with('parentRelation'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $data;
    }

    public function renderType()
    {
        $typeItems = self::typeItems();
        return isset($typeItems[$this->type]) ? $typeItems[$this->type] : Yii::t('app', 'Unknown menu type');
    }

    public function renderLevel()
    {
        $levelItems = self::levelItems();
        return isset($levelItems[$this->level]) ? $levelItems[$this->level] : Yii::t('app', 'Unknown menu level');
    }

    public function renderEnabled()
    {
        $enabledItems = self::enabledItems();
        return isset($enabledItems[$this->enabled]) ? $enabledItems[$this->enabled] : Yii::t('app', 'Unknown menu enabled status');
    }

    public function renderParentTitle()
    {
        return ($this->parentRelation) ? $this->parentRelation->title : Yii::t('app', 'No parent');
    }
}
