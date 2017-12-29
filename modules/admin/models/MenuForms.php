<?php

namespace app\modules\admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\Menu;
use app\models\CustomModel as Model;
use app\widgets\Ajax;

class MenuForms extends Model
{
    const SCENARIO_MENU_CREATE = 'menu_create';
    const SCENARIO_MENU_EDIT = 'menu_edit';
    const SCENARIO_MENU_DELETE = 'menu_delete';

    /**
     *  Child conversion NOTES
     *
     *  predefinded constants what to do if root (parent for some childs) have change
     *  their type and/or parent (means parent become child, level attribute changed)
     *
     * _NO means that parent not change their values
     */
    const CHILD_TYPE_CONVERSION_NO = 0;
    const CHILD_TYPE_CONVERSION_REMOVE = 1;
    const CHILD_TYPE_CONVERSION_UPDATE = 2;
    const CHILD_TYPE_CONVERSION_HOLD = 3;

    const CHILD_PARENT_CONVERSION_NO = 0;
    const CHILD_PARENT_CONVERSION_REMOVE = 1;
    const CHILD_PARENT_CONVERSION_MOVE = 2;
    const CHILD_PARENT_CONVERSION_ROOT = 3;

    /* menu related attributes */
    // id default to 0 to prevent in sql queries compare != NULL
    public $id = 0;
    public $type;
    public $level;
    public $parent;
    public $order;
    public $enabled;
    public $title;
    /* special form attributes */
    public $childTypeConversion = self::CHILD_TYPE_CONVERSION_NO;
    public $childParentConversion = self::CHILD_PARENT_CONVERSION_NO;


    public function init()
    {
        parent::init();

        $this->type = Menu::defaultType();
        $this->level = Menu::defaultLevel();
        $this->parent = Menu::defaultParent();
        $this->order = Menu::defaultOrder();
        $this->enabled = Menu::defaultEnabled();
    }

    public function scenarios()
    {
        $attributes = $this->attributes();

        $concat = array_merge(parent::scenarios(), [
            self::SCENARIO_MENU_CREATE => ['type', 'level', 'parent', 'order', 'title', 'enabled'],
            self::SCENARIO_MENU_EDIT => ['id', 'type', 'childTypeConversion', 'level', 'childParentConversion', 'parent', 'order', 'enabled', 'title'],
            self::SCENARIO_MENU_DELETE => ['id'],
        ]);

        return $concat;
    }

    public function formName()
    {
        return $this->getScenario();
    }

    public function rules()
    {
        return [
            [['id', 'type', 'level', 'parent', 'order', 'enabled', 'title', 'childTypeConversion', 'childParentConversion'], 'required'],
            [['id', 'order'], 'integer', 'min' => 1],
            ['title', 'string'],
        ];
    }

    public function dynamicRules()
    {
        return [
            ['type', 'in', 'range' => array_keys($this->typeItems())],
            ['level', 'in', 'range' => array_keys($this->levelItems())],
            ['parent', 'in', 'range' => array_keys($this->parentItems())],
            ['enabled', 'in', 'range' => array_keys($this->enabledItems())],
            ['childTypeConversion', 'in', 'range' => array_keys($this->childTypeConversionItems())],
            ['childParentConversion', 'in', 'range' => array_keys($this->childParentConversionItems())],
        ];
    }

    public function typeItems()
    {
        return Menu::typeItems();
    }

    public function levelItems()
    {
        $items = Menu::levelItems();
        if (!Menu::find()->where(['type' => $this->type, 'level' => Menu::LEVEL_ROOT])->andWhere(['!=', 'id', $this->id])->count()) {
            unset($items[Menu::LEVEL_CHILD_1]);
        }

        return $items;
    }

    public function parentItems()
    {
        if ($this->level == Menu::LEVEL_ROOT) {
            return [0 => Yii::t('app', 'Root cannot have parent')];
        }

        $items = Menu::find()->where(['type' => $this->type, 'level' => Menu::LEVEL_ROOT])->andWhere(['!=', 'id', $this->id])->asArray()->all();

        return ArrayHelper::map($items, 'id', 'title');
    }

    public function enabledItems()
    {
        return Menu::enabledItems();
    }

    public function childTypeConversionItems()
    {
        if (
            $this->childParentConversion == self::CHILD_PARENT_CONVERSION_REMOVE ||
            $this->type == Menu::find()->select('type')->where(['id' => $this->id])->scalar() ||
            !Menu::find()->where(['parent' => $this->id])->count()
        ) {
            return [self::CHILD_TYPE_CONVERSION_NO => Yii::t('app', 'Not need child type conversion')];
        }

        $items = [
            self::CHILD_TYPE_CONVERSION_UPDATE => Yii::t('app', 'Update childs type'),
            self::CHILD_TYPE_CONVERSION_HOLD => Yii::t('app', 'Not change childs type'),
            self::CHILD_TYPE_CONVERSION_REMOVE => Yii::t('app', 'Remove childs'),
        ];

        if ($this->level == Menu::LEVEL_ROOT) {
            unset($items[self::CHILD_TYPE_CONVERSION_HOLD]);
        }

        if ($this->childParentConversion == self::CHILD_PARENT_CONVERSION_MOVE) {
            unset($items[self::CHILD_TYPE_CONVERSION_HOLD]);
        }

        return $items;
    }

    public function childParentConversionItems()
    {
        if (
            $this->level == Menu::LEVEL_ROOT ||
            $this->childTypeConversion == self::CHILD_TYPE_CONVERSION_REMOVE ||
            !Menu::find()->where(['parent' => $this->id])->count()
        ) {
            return [self::CHILD_PARENT_CONVERSION_NO => Yii::t('app', 'Not need child parent convertion')];
        }

        $items = [
            self::CHILD_PARENT_CONVERSION_ROOT => Yii::t('app', 'Convert childs to roots'),
            self::CHILD_PARENT_CONVERSION_MOVE => Yii::t('app', 'Move childs to same root'),
            self::CHILD_PARENT_CONVERSION_REMOVE => Yii::t('app', 'Remove childs'),
        ];

        if ($this->childTypeConversion == self::CHILD_TYPE_CONVERSION_HOLD) {
            unset($items[self::CHILD_PARENT_CONVERSION_MOVE]);
        }

        return $items;
    }

    public function renderTypeField($form)
    {
        return $form->field($this, 'type')->dropDownList(Menu::typeItems(), ['onchange' => Ajax::post(['admin/menu/update-model', 'scenario' => $this->getScenario()])]);
    }

    public function renderLevelField($form)
    {
        $levelItems = $this->levelItems();
        if (!$levelItems) return '';
        return $form->field($this, 'level')->dropDownList($levelItems, ['onchange' => Ajax::post(['/admin/menu/update-model', 'scenario' => $this->getScenario()])]);
    }

    public function renderParentField($form)
    {
        $parentItems = $this->parentItems();
        if ($this->level == Menu::LEVEL_ROOT || !$parentItems) return '';
        return $form->field($this, 'parent')->dropDownList($parentItems);
    }

    public function renderEnabledField($form)
    {
        return $form->field($this, 'enabled')->radioList($this->enabledItems());
    }

    public function renderChildTypeConversionField($form)
    {
        $items = $this->childTypeConversionItems();
        if (isset($items[self::CHILD_TYPE_CONVERSION_NO])) return '';
        if (!$this->childTypeConversion) $this->childTypeConversion = key($items);
        return $form->field($this, 'childTypeConversion')->radioList($items, ['onchange' => Ajax::post(['admin/menu/update-model', 'scenario' => $this->getScenario()])]);
    }

    public function renderChildParentConversionField($form)
    {
        $items = $this->childParentConversionItems();
        if (isset($items[self::CHILD_PARENT_CONVERSION_NO])) return '';
        if (!$this->childParentConversion) $this->childParentConversion = key($items);
        return $form->field($this, 'childParentConversion')->radioList($items, ['onchange' => Ajax::post(['admin/menu/update-model', 'scenario' => $this->getScenario()])]);
    }

    public function requestCreate()
    {

        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $menu = new Menu();
            $menu->setAttributes($this->getAttributes($this->activeAttributes()));

            if ($menu->save()) {
                return true;
            }
        }

        return false;
    }

    public function requestEdit(&$menu)
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $menu->setAttributes($this->getAttributes($this->activeAttributes()));

            // profilact update all childs if type

            $isChildConversionSuccess = true;

            if (
                $this->childTypeConversion == self::CHILD_TYPE_CONVERSION_REMOVE ||
                $this->childParentConversion == self::CHILD_PARENT_CONVERSION_REMOVE
            ) {
                $isChildConversionSuccess = Menu::deleteAll(['parent' => $this->id]);
            } else {
                $updateAttributes = [];
                if ($this->childTypeConversion == self::CHILD_TYPE_CONVERSION_UPDATE) {
                    $updateAttributes['type'] = $this->type;
                }
                switch ($this->childParentConversion) {
                    case self::CHILD_PARENT_CONVERSION_MOVE: $updateAttributes['parent'] = $this->parent; break;
                    case self::CHILD_PARENT_CONVERSION_ROOT: $updateAttributes['parent'] = Menu::PARENT_NO; $updateAttributes['level'] = Menu::LEVEL_ROOT; break;
                }

                if ($updateAttributes) {
                    $isChildConversionSuccess = Menu::updateAll($updateAttributes, ['parent' => $this->id]);
                }
            }



            if ($isChildConversionSuccess && $menu->save()) {
                return true;
            }
        }

        return false;
    }

    public function requestDelete()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $menu = Menu::findOne($this->id);

            if ($menu) {
                if ($menu->level == Menu::LEVEL_ROOT) {
                    Menu::deleteAll(['parent' => $this->id]);
                }

                $menu->delete();

                return true;
            }
        }

        return false;
    }
}
