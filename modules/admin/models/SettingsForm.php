<?php

namespace app\modules\admin\models;

use Yii;
use app\models\CustomModel as Model;
use app\models\Settings;
use app\modules\admin\Module;

class SettingsForm extends Model
{
    // is site under maintance, default true on first app install
    // can be change by admin
    public $maintance;

    public function init()
    {
        $predefinedSettings = Settings::predefinedSettings();
        foreach ($this->attributes() as $attributeName) {
            if (isset($predefinedSettings[$attributeName])) {
                $this->$attributeName = $predefinedSettings[$attributeName];
            }
        }
    }

    public function rules()
    {
        return [
            // all public attributes required
            [$this->attributes(), 'required'],
            // bool attributes validator
            [['maintance'], 'in', 'range' => [0, 1]],
        ];
    }

    public function requestSave()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $existDbNames = Settings::selectAll();
            foreach ($this->attributes() as $attribute) {
                if (isset($existDbNames[$attribute])) {
                    if ($this->$attribute != $existDbNames[$attribute]) {
                        Settings::getDb()->createCommand()->update(Settings::tableName(), ['value' => $this->$attribute], ['name' => $attribute])->execute();
                    }
                    unset($existDbNames[$attribute]);
                } else {
                    Settings::getDb()->createCommand()->insert(Settings::tableName(), ['name' => $attribute, 'value' => $this->$attribute])->execute();
                }
            }

            if (!empty($existDbNames)) {
                Settings::deleteAll(['name' => array_keys($existDbNames)]);
            }

            return true;
        }

        return false;
    }

    public function renderMaintanceField($form)
    {
        return $form->field($this, 'maintance')->checkbox();
    }

    public function attributeLabels()
    {
        return [
            'maintance' => Module::t('main', 'Maintance'),
        ];
    }

    public function attributeHints()
    {
        return [
            'maintance' => Module::t('main', 'Is site under maintance, checked mean to disable user interract with site (except users with administator role)'),
        ];
    }
}
