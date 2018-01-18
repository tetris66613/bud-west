<?php

namespace app\modules\admin\controllers;

use app\modules\admin\controllers\AdminController;
use app\modules\admin\models\SettingsForm;
use app\models\Settings;

class SettingsController extends AdminController
{
    public function actionIndex()
    {
        $model = new SettingsForm();
        $model->setAttributes(Settings::selectAll());

        if ($model->requestSave()) {
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
