<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use app\models\Menu;
use app\widgets\Ajax;

class MenuForms extends Model
{
    const SCENARIO_MENU_CREATE = 'menu_create';
    const SCENARIO_MENU_EDIT = 'menu_edit';
    const SCENARIO_MENU_DELETE = 'menu_delete';

    public $id;
    public $type;
    public $level;
    public $parent;
    public $order;
    public $enabled;
    public $title;

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
        $attributes = self::attributes();

        return array_merge(parent::scenarios(), [
            self::SCENARIO_MENU_CREATE => array_diff($attributes, ['id']),
            self::SCENARIO_MENU_EDIT => $attributes,
            self::SCENARIO_MENU_DELETE => ['id'],
        ]);
    }

    public function formName()
    {
        return $this->getScenario();
    }

    public function rules()
    {
        return [
            [['type', 'level', 'parent', 'order', 'enabled', 'title'], 'required'],
            ['type', 'in', 'range' => array_keys($this->typeItems())],
            ['level', 'in', 'range' => array_keys($this->levelItems())],
            ['parent', 'in', 'range' => array_keys($this->parentItems())],
            ['enabled', 'in', 'range' => array_keys($this->enabledItems())],
            ['order', 'integer', 'min' => 1],
            ['title', 'string'],
        ];
    }


    public function typeItems()
    {
        return Menu::typeItems();
    }

    public function levelItems()
    {
        $items = Menu::levelItems();
        if (!Menu::find()->where(['type' => $this->type, 'level' => Menu::LEVEL_ROOT])->count()) {
            unset($items[Menu::LEVEL_CHILD_1]);
        }

        return $items;
    }

    public function parentItems()
    {
        if ($this->level == Menu::LEVEL_ROOT) {
            return [0 => Yii::t('app', 'Root cannot have parent')];
        }

        $items = Menu::find()->where(['type' => $this->type, 'level' => Menu::LEVEL_ROOT])->asArray()->all();
        return ArrayHelper::map($items, 'id', 'title');
    }

    public function enabledItems()
    {
        return Menu::enabledItems();
    }

    public function renderFormFields($form, $attributeWrappers = [])
    {
        $content = '';
        foreach (self::activeAttributes() as $attribute) {
            $ucattr = ucfirst($attribute);
            $fieldMethod = "render${ucattr}Field";
            if (method_exists($this, $fieldMethod)) {
                $content .= $this->$fieldMethod($form);
            } else {
                $content .= $form->field($this, $attribute);
            }
        }

        return $content;
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
}
