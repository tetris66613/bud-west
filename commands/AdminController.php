<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class AdminController extends Controller
{
    /**
     * This command creates admin for the project (more useful for creating first admin)
     */
    public function actionCreate($email, $password)
    {
        if (User::findByEmail($email)) {
            echo 'user already exists with this email: '. $email . PHP_EOL;
            return 0;
        }

        $user = new User();
        $user->email = $email;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $user->role = User::ROLE_ADMIN;

        if (!$user->insert()) {
            echo 'user cannot be created, some problem' . PHP_EOL;
            return 0;
        }

        echo 'successfull created new admin:' . $email . PHP_EOL;

        return 1;
    }

    public function actionResetPassword($email, $newpassword)
    {
        $user = User::findByEmail($email);
        if (!$user) {
            echo 'cannot find user for this email: ' . $email . PHP_EOL;
            return 0;
        }

        $user->password = Yii::$app->getSecurity()->generatePasswordHash($newpassword);
        if (!$user->update()) {
            echo 'cannot reset user, some problem' . PHP_EOL;
        }

        echo 'successfull reset password for admin: ' . $email . PHP_EOL;
    }
}
