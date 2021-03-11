<?php

namespace app\models;
use Yii;
use dektrium\user\models\LoginForm as BaseLoginForm;


/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends BaseLoginForm
{
    public function login()
    {
        if ($this->validate() && $this->user) {
            $isLogged = Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);           

            return $isLogged;
        }

        return false;
    }
}
