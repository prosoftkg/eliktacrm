<?php

namespace app\models;

use Yii;
use app\models\Company;

use dektrium\user\models\User as BaseUser;

class User extends BaseUser
{
    public $name;
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // add field to scenarios
        $scenarios['create'][] = 'company_id';
        $scenarios['update'][] = 'company_id';
        $scenarios['register'][] = 'company_id';

        $scenarios['create'][] = 'parent_id';
        $scenarios['update'][] = 'parent_id';
        $scenarios['register'][] = 'parent_id';
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        // add some rules
        $rules['fieldRequired'] = [['company_id', 'parent_id', 'name'], 'safe'];
        $rules['fieldLength'] = ['company_id', 'integer', 'max' => 100];
        $rules['fieldLength'] = ['name', 'string', 'max' => 100];
        $rules['fieldLength'] = ['parent_id', 'integer'];
        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя и Фамилия',
            'username' => 'Имя пользователя',
            'email' => 'Эл.почта',
            'created_at' => 'Дата регистрации',
            'created_at' => 'Дата регистрации',
            'password' => 'Пароль',
        ];
    }

    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    public function getOwned()
    {
    }
}
