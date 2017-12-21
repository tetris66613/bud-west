<?php

namespace app\models;

use Yii;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;
    const ROLE_DEMO = 3;

    public static function rolesItems()
    {
        return [
            self::ROLE_ADMIN => Yii::t('app', 'Administrator'),
            self::ROLE_USER => Yii::t('app', 'User'),
            self::ROLE_DEMO => Yii::t('app', 'Demo'),
        ];
    }

    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authkey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authkey)
    {
        return $this->authkey === $authkey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     *  Begin block with checking user role
     */

    protected function prepareCheckUser($user = null)
    {
        return $user ? $user : $this;
    }

    public function isAdmin($user = null)
    {
        return self::checkIsAdmin($this->prepareCheckUser($user));
    }

    public function isUser($user = null)
    {
        return self::checkIsUser($this->prepareCheckUser($user));
    }

    public function isDemo($user = null)
    {
        return self::checkIsDemo($this->prepareCheckUser($user));
    }

    public static function checkIsAdmin($user = null)
    {
        return self::checkRole(User::ROLE_ADMIN, $user);
    }

    public static function checkIsUser($user = null)
    {
        return self::checkRole(User::ROLE_USER, $user);
    }

    public static function checkIsDemo($user = null)
    {
        return self::checkRole(User::ROLE_DEMO, $user);
    }

    public static function checkRole($role, $user = null)
    {
        if (!$user) {
            if (Yii::$app->user->isGuest) return false;
            $user = Yii::$app->user->identity;
        }

        if (is_array($role)) {
            return in_array($user->role, $role);
        } else {
            return $user->role === $role;
        }
    }

    /**
     *  End block with checking user role
     */

    public function renderRole()
    {
        return self::sRenderRole($this);
    }

    public static function sRenderRole($data)
    {
        $key = is_int($data) ? $data : $data['role'];

        return isset(self::rolesItems()[$key]) ? self::rolesItems()[$key] : Yii::t('app', 'Unknown role');
    }
}
