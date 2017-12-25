<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use app\models\Menu;

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
        return $form->field($this, 'type')->dropDownList(Menu::typeItems());
    }

    public function renderLevelField($form)
    {
        return $form->field($this, 'level')->dropDownList(Menu::levelItems());
    }

    public function renderEnabledField($form)
    {
        return $form->field($this, 'enabled')->radioList(Menu::enabledItems());
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
