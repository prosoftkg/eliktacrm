<?php

namespace app\models;

use Yii;
use app\models\Company;
use dektrium\user\models\User as BaseUser;

/**
 * This is the model class for table "apartment".
 *
 * @property string $firebase_id
 * @property string $phone
 */
class User extends BaseUser
{
    public $name;
    //public $firebase_id;
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
        $rules['fieldSafe'] = [['company_id', 'parent_id', 'name', 'firebase_id', 'phone'], 'safe'];
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

    /**
     * @param $token
     * @param null $type
     * @return User|void|\yii\web\IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::findOne(['auth_key' => $token]);
        if ($user !== null && !$user->isBlocked && $user->isConfirmed) {
            return $user;
        }

        return null;
    }

    public static function pushNotification($token, $params)
    {
        $fcm_server_key = 'AAAAkGfPZwQ:APA91bF7kzDYW-kVVnTJDCazMhaP4pMc8ZVo-BmE_JLpQZTBE8R_0hmoKDvq8sRZ3z4pOoSYvWWqrEwWJ6OFvwFKLFhhdGm_bMh8-C-ko4jfty1DZvlbESkmU7SSeeiT94y0FXH2s7db';

        $data = ["click_action" => "FLUTTER_NOTIFICATION_CLICK"];
        if (!empty($params['params'])) {
            $data = array_merge($data, $params['params']);
        }
        $fields = [
            'notification'     => ['body' => $params['body'], 'title' => $params['title'], 'sound' => 'default'],
            'priority' => 'high',
            'data' => $data,
            'to' => $token
        ];


        $headers = ['Authorization: key=' . $fcm_server_key, 'Content-Type: application/json'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }
}
