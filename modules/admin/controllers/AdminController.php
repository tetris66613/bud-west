<?php

/**
 *  Main controller without actions that must be parent for
 *  all other controllers for this module that need
 *  to apply some access rules or another staff
 *
 *  For now simple uses to all childs that must check is action called by admin
 */

namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use app\models\User;

class AdminController extends Controller
{
    public function beforeAction($action)
    {
        if (!User::checkIsAdmin()) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        return parent::beforeAction($action);
    }
}
