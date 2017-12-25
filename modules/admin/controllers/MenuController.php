<?php

namespace app\modules\admin\controllers;

use app\models\Menu;
use app\modules\admin\controllers\AdminController;
use app\modules\admin\models\MenuForms;

class MenuController extends AdminController
{
    public function actionIndex()
    {
        $menuData = Menu::gridData();

        return $this->render('index', [
            'menuData' => $menuData,
        ]);
    }

    public function actionCreate()
    {
        $model = new MenuForms(['scenario' => MenuForms::SCENARIO_MENU_CREATE]);

        if ($model->requestCreate()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
