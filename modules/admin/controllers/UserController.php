<?php

namespace app\modules\admin\controllers;

use yii\data\ActiveDataProvider;
use app\modules\admin\controllers\AdminController;
use app\models\User;

class UserController extends AdminController
{
    public function actionIndex()
    {
        $usersData = new ActiveDataProvider([
            'query' => User::find()->select(['id', 'email', 'role']),
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);

        return $this->render('index', [
            'usersData' => $usersData,
        ]);
    }
}
