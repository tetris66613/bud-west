<?php

namespace app\widgets;

use yii\base\Widget;
use yii\bootstrap\ActiveForm;

class DeleteForm extends Widget
{
    public $formClass;
    public $action;
    public $model;

    public function init()
    {
        parent::init();

        $this->formClass = ActiveForm::className();
        $this->action = ['delete'];
    }

    public function run()
    {
        return $this->render('delete-form', [
            'widget' => $this,
        ]);
    }
}