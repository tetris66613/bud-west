<?php

namespace app\modules\admin\controllers;

use Yii;
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

    public function actionUpdateModel($scenario)
    {
        $model = new MenuForms(['scenario' => $scenario]);
        $model->load(Yii::$app->request->post());

        $content = $this->renderPartial('form', [
            'model' => $model,
        ]);

        return preg_replace(['/<form.+?>/', '/<\/form>/'], '', $content);
    }
}
