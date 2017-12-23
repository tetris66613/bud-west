<?php

namespace app\commands;

use Yii;
use yii\base\Security;
use yii\console\Controller;
use app\models\User;

class AdminController extends Controller
{
    /**
     * This command creates admin for the project (more useful for creating first admin)
     */
    public function actionCreate($email, $password)
    {
        $security = Yii::$app->getSecurity();
        $user = new User();
        $user->email = $email;
        $user->password = $security->generatePasswordHash($password);
        $user->authkey = $security->generateRandomString();
        $user->role = User::ROLE_ADMIN;

        if (!$user->insert()) {
            echo 'user cannot be created, some problem' . PHP_EOL;
            if ($user->hasErrors()) {
                foreach ($user->getErrors() as $attr => $errors) {
                    echo "Attribute $attr has errors:" . PHP_EOL;
                    foreach ($errors as $error) {
                        echo "\t$error" . PHP_EOL;
                    }
                }
            }
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

        $security = Yii::$app->getSecurity();
        $user->password = $security->generatePasswordHash($newpassword);
        $user->authkey = $security->generateRandomString();
        if (!$user->update()) {
            echo 'cannot reset user, some problem' . PHP_EOL;
        }

        echo 'successfull reset password for admin: ' . $email . PHP_EOL;
    }
}
