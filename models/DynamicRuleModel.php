<?php

namespace app\models;

use yii\base\Model;
use yii\validators\Validator;

class DynamicRuleModel extends Model
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
}
