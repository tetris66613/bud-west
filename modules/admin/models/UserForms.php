<?php

namespace app\modules\admin\models;

use Yii;
use app\models\CustomModel as Model;
use app\models\User;
use app\modules\admin\Module;

class UserForms extends Model
{
    const SCENARIO_USER_CREATE = 'user_create';
    const SCENARIO_USER_EDIT = 'user_edit';
    const SCENARIO_USER_SELFEDIT = 'user_selfedit';

    public $id;
    public $email;
    public $username;
    public $role = User::ROLE_ADMIN;
    public $password;
    public $changePassword;
    public $passwordConfirm;

    protected $curUser = 0;

    public function scenarios()
    {
        $parentScenarios = parent::scenarios();

        $scenarios = [
            self::SCENARIO_USER_CREATE => ['email', 'username', 'role', 'password', 'passwordConfirm'],
            self::SCENARIO_USER_EDIT => ['id', 'email', 'username', 'role', 'changePassword', 'passwordConfirm'],
            // not allow change role for themself, example - make themself not admin
            self::SCENARIO_USER_SELFEDIT => ['id', 'email', 'username', 'changePassword', 'passwordConfirm'],
        ];

        return $scenarios + parent::scenarios();
    }

    public function formName()
    {
        return $this->getScenario();
    }

    public function attributeLabels()
    {
        return array_merge(User::attributeLabels(), [
            'password' => Module::t('main', 'Password'),
            'changePassword' => Module::t('main', 'Change password'),
            'passwordConfirm' => Module::t('main', 'Password repeat'),
        ]);
    }

    public function rules()
    {
        return [
            [['email', 'role'], 'required'],
            ['password', 'required', 'on' => [self::SCENARIO_USER_CREATE]],
            ['email', 'email'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'default'],
            [['email', 'username'], 'unique', 'targetClass' => User::className(), 'on' => [self::SCENARIO_USER_CREATE]],
            [['email', 'username'], 'validateUniqueOnChangeOnly', 'on' => [self::SCENARIO_USER_EDIT, self::SCENARIO_USER_SELFEDIT]],
            ['role', 'in', 'range' => array_keys(User::rolesItems())],
            [['password', 'changePassword', 'passwordConfirm'], 'filter', 'filter' => 'trim'],
            [['password', 'changePassword'], 'validateChangePassword'],
        ];
    }

    public function validateChangePassword($attr, $params)
    {
        if (empty($this->$attr)) {
            return;
        }
        if ($this->$attr !== $this->passwordConfirm) {
            $this->addError($attr, Module::t('main', 'Password mismatch'));
        }
    }

    public function validateUniqueOnChangeOnly($attr, $params)
    {
        if ($this->curUser === 0) {
            $this->curUser = User::findOne($this->id);
            if (!$this->curUser) {
                $this->addError($attr, Module::t('main', 'Cannot find user to edit'));
                return false;
            }
        } else if (!$this->curUser) return;

        $value = $this->$attr;

        if ($this->curUser->$attr != $value) {
            if (User::findByAttribute($attr, $value)) {
                $this->addError($attr, Yii::t('yii', '{attribute} "{value}" has already been taken.', ['attribute' => $this->getAttributeLabel($attr), 'value' => $value]));
                return false;
            }
        }
    }

    public function rolesItems()
    {
        return User::rolesItems();
    }

    public function renderRoleField($form)
    {
        return $form->field($this, 'role')->dropDownList($this->rolesItems());
    }

    public function renderPasswordField($form)
    {
        return $this->renderPasswordInput($form, 'password');
    }

    public function renderChangePasswordField($form)
    {
        return $this->renderPasswordInput($form, 'changePassword');
    }

    public function renderPasswordConfirmField($form)
    {
        return $this->renderPasswordInput($form, 'passwordConfirm');
    }

    public function renderPasswordInput($form, $attribute)
    {
        return $form->field($this, $attribute)->passwordInput();
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
