<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord
{
    // predefined menus types
    const TYPE_CLIENT_NAVBAR = 1;
    const TYPE_SIDEBAR = 2;

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

    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'Type'),
            'level' => Yii::t('app', 'Level'),
            'parent' => Yii::t('app', 'Parent'),
            'order' => Yii::t('app', 'Order'),
            'enabled' => Yii::t('app', 'Enabled'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    public function getParentRelation()
    {
        return $this->hasOne(self::className(), ['id' => 'parent']);
    }

    public function getChildsRelation()
    {
        return $this->hasMany(self::className(), ['parent' => 'id']);
    }

    public function getArticleRelation()
    {
        return $this->hasOne(ArticleRelate::className(), ['related_id' => 'id']);
    }

    public static function selectEnabledItems($type)
    {
        return self::find()
            ->with('childsRelation')
            ->where([
                'type' => $type,
                'enabled' => self::ENABLED_TRUE,
                'parent' => self::PARENT_NO
            ])
            ->orderBy(['order' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function buildNavItems($type, $options = [])
    {
        $records = self::selectEnabledItems($type);
        $items = [];
        $linkOptions = isset($options['linkOptions']) ? $options['linkOptions'] : [];
        foreach ($records as $record) {
            if (!empty($record['childsRelation'])) {
                $subItems = [];
                foreach ($record['childsRelation'] as $child) {
                    $subItems[] = [
                        'label' => $child['title'],
                        'url' => ['menu/view', 'id' => $child['id']],
                        'linkOptions' => $linkOptions,
                    ];
                }
                $items[] = ['label' => $record['title'], 'items' => $subItems];
            } else {
                $items[] = [
                    'label' => $record['title'],
                    'url' => ['menu/view', 'id' => $record['id']],
                    'linkOptions' => $linkOptions,
                ];
            }

        }

        return $items;

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
            self::TYPE_SIDEBAR => Yii::t('app', 'Sidebar'),
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
        if ($this->parentRelation) {
            return $this->parentRelation->title;
        } elseif ($this->parent != Menu::PARENT_NO) {
            return $this->parent;
        } else {
            return Yii::t('app', 'No parent');
        }
    }
}
