<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Settings;
use app\models\User;

class ClientController extends Controller
{
    public function beforeAction($action)
    {
        if (Settings::isMaintance() && !User::checkIsAdmin()) {
            $uniqueId = $action->getUniqueId();
            if (!in_array($uniqueId, ['site/error', 'site/login', 'site/logout', 'site/maintance'])) {
                $this->redirect(['site/maintance']);
                return false;
            }
        }

        return parent::beforeAction($action);
    }
}
