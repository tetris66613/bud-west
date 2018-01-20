<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Settings;
use app\models\User;


/**
 * Main controller for at least this folder, else controllers must extends from this
 * to achieve functionality with maintance mode
 */
class ClientController extends Controller
{
    public function beforeAction($action)
    {
        if (Settings::isMaintance() && !User::checkIsAdmin()) {
            $uniqueId = $action->getUniqueId();
            if (!in_array($uniqueId, ['site/error', 'site/login', 'site/logout', 'site/maintance'])) {
                Yii::$app->session->setFlash('maintance-redirect', '1');
                $this->redirect(['site/maintance']);
                return false;
            }
        }

        return parent::beforeAction($action);
    }
}
