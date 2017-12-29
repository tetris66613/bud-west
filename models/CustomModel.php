<?php

namespace app\models;

use yii\base\Model;
use yii\validators\Validator;

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

    public function renderFormFields($form, $attributeWrappers = [])
    {
        $content = '';
        foreach (self::activeAttributes() as $attribute) {
            $ucattr = ucfirst($attribute);
            $fieldMethod = "render${ucattr}Field";
            if (method_exists($this, $fieldMethod)) {
                $content .= $this->$fieldMethod($form);
            } else {
                if ($attribute == 'id') {
                    $content .= $form->field($this, $attribute)->hiddenInput()->label(false);
                } else {
                    $content .= $form->field($this, $attribute);
                }
            }
        }

        return $content;
    }

    public function getActiveAttributes() {
        return $this->getAttributes($this->activeAttributes());
    }
}
