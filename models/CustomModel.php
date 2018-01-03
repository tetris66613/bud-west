<?php

namespace app\models;

use yii\base\Model;
use yii\validators\Validator;
use app\helpers\Html;

class CustomModel extends Model
{
    public function dynamicRules()
    {
        return [];
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        foreach ($this->dynamicRules() as $dynamicRule) {
            $this->addRule($dynamicRule[0], $dynamicRule[1], array_slice($dynamicRule, 2));
        }

        return parent::validate($attributeNames, $clearErrors);
    }

    public function addRule($attributes, $validator, $options = [])
    {
        $validators = $this->getValidators();
        $validators->append(Validator::createValidator($validator, $this, (array) $attributes, $options));

        return $this;
    }

    public function renderFormFields($form, $attributes = [], $attributeWrappers = [])
    {
        $content = '';
        if (!$attributes) $attributes = self::activeAttributes();
        foreach ($attributes as $attribute) {
            $attributeContent = '';
            $ucattr = ucfirst($attribute);
            $fieldMethod = "render${ucattr}Field";
            if (method_exists($this, $fieldMethod)) {
                $attributeContent .= $this->$fieldMethod($form);
            } else {
                if ($attribute == 'id') {
                    $attributeContent .= $form->field($this, $attribute)->hiddenInput()->label(false);
                } else {
                    $attributeContent .= $form->field($this, $attribute);
                }
            }

            $prepend = '<' . $attribute;
            if (isset($attributeWrappers[$prepend])) {
                $attributeContent = Html::prepend($attributeContent, $attributeWrappers[$prepend]);
            }
            $append = '>' . $attribute;
            if (isset($attributeWrappers[$append])) {
                $attributeContent = Html::append($attributeContent, $attributeWrappers[$append]);
            }
            if (isset($attributeWrappers[$attribute])) {
                $attributeContent = Html::wrap($attributeContent, $attributeWrappers[$attribute]);
            }

            $content .= $attributeContent;

        }

        return $content;
    }

    public function getActiveAttributes() {
        return $this->getAttributes($this->activeAttributes());
    }
}
