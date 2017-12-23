<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use app\models\User;
use app\modules\admin\Module;
use app\modules\admin\controllers\AdminController;
use app\modules\admin\models\UserForms;

class UserController extends AdminController
{
    public function actionIndex()
    {
        $usersData = new ActiveDataProvider([
            'query' => User::find()->select(['id', 'email', 'username', 'role']),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'usersData' => $usersData,
        ]);
    }

    public function actionCreate()
    {
        $model = new UserForms(['scenario' => UserForms::SCENARIO_USER_CREATE]);

        if ($model->requestCreate()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionSelfEdit($id)
    {
        if ($id != Yii::$app->user->identity->id) {
            return $this->redirect(['edit', 'id' => $id]);
        }

        return $this->edit($id, UserForms::SCENARIO_USER_SELFEDIT);
    }

    public function actionEdit($id)
    {
        if ($id == Yii::$app->user->identity->id) {
            return $this->redirect(['self-edit', 'id' => $id]);
        }

        return $this->edit($id, UserForms::SCENARIO_USER_EDIT);
    }

    public function edit($id, $scenario)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException(Module::t('main', 'User not found'));
        }
        $model = new UserForms(['scenario' => $scenario]);

        if ($model->requestEdit($user)) {
            return $this->redirect(['index']);
        }

        $model->setAttributes($user->getAttributes());


        return $this->render('edit', ['model' => $model]);
    }
}
