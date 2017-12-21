<?php

namespace app\modules\admin\controllers;

use app\modules\admin\controllers\AdminController;

class DefaultController extends AdminController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
