<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\Menu;
use app\modules\admin\Module;
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

    public function actionEdit($id)
    {
        $menu = Menu::findOne($id);

        if (!$menu) {
            throw new NotFoundHttpException(Module::t('main', 'Menu not found'));
        }

        $model = new MenuForms(['scenario' => MenuForms::SCENARIO_MENU_EDIT]);

        if ($model->requestEdit($menu)) {
            return $this->redirect(['index']);
        }


        $model->setAttributes($menu->getAttributes());
        $modelDelete = new MenuForms(['scenario' => MenuForms::SCENARIO_MENU_DELETE]);
        $modelDelete->setAttributes($menu->getAttributes());

        return $this->render('edit', [
            'model' => $model,
            'modelDelete' => $modelDelete,
        ]);
    }

    public function actionDelete()
    {
        $model = new MenuForms(['scenario' => MenuForms::SCENARIO_MENU_DELETE]);

        if ($model->requestDelete()) {
            return $this->redirect(['index']);
        }

        throw new NotFoundHttpException(Module::t('main', 'Menu not found'));
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
