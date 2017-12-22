<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\modules\admin\Module;

class UserForms extends Model
{
    const SCENARIO_USER_CREATE = 'user_create';
    const SCENARIO_USER_EDIT = 'user_edit';
    const SCENARIO_USER_SELFEDIT = 'user_selfedit';

    public $email;
    public $username;
    public $role = User::ROLE_ADMIN;
    public $password;
    public $changePassword;
    public $changePasswordConfirm;

    public function scenarios()
    {
        $parentScenarios = parent::scenarios();

        $scenarios = [
            self::SCENARIO_USER_CREATE => ['email', 'username', 'role', 'password'],
            self::SCENARIO_USER_EDIT => ['email', 'username', 'role', 'changePassword', 'changePasswordConfirm'],
            // not allow change role for themself, example - make themself not admin
            self::SCENARIO_USER_SELFEDIT => ['email', 'username', 'changePassword', 'changePasswordConfirm'],
        ];

        return $scenarios + parent::scenarios();
    }

    public function formName()
    {
        return $this->getScenario();
    }

    public function rules()
    {
        return [
            [['email', 'role'], 'required'],
            ['password', 'required', 'on' => [self::SCENARIO_USER_CREATE]],
            ['role', 'in', 'range' => array_keys(User::rolesItems())],
            [['changePassword', 'changePasswordConfirm'], 'filter', 'filter' => 'trim'],
            ['changePassword', 'validateChangePassword'],
        ];
    }

    public function validateChangePassword($attr, $params)
    {
        if (empty($this->$attr)) {
            return;
        }
        if ($this->$attr !== $this->changePasswordConfirm) {
            $this->addError($attr, Module::t('main', 'Password mismatch'));
        }
    }

    public function rolesItems()
    {
        return User::rolesItems();
    }

    public function requestCreate()
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $user = new User();
            $user->setAttributes($this->getAttributes($this->activeAttributes()));
            $security = Yii::$app->getSecurity();
            $user->password = $security->generatePasswordHash($this->password);
            $user->authkey = $security->generateRandomString();

            if ($user->save()) {
                return true;
            }
        }

        return false;
    }

    public function requestEdit(&$user)
    {
        if ($this->load(Yii::$app->request->post()) && $this->validate()) {
            $user->setAttributes($this->getAttributes($this->activeAttributes()));
            if (!empty($this->changePassword)) {
                $security = Yii::$app->getSecurity();
                $user->password = $security->generatePasswordHash($this->changePassword);
                $user->authkey = $security->generateRandomString();
            }

            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}
