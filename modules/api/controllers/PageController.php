<?php

namespace app\modules\api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\Profile;

class PageController extends BaseController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        return [];
    }

    //login or register
    public function actionAuth()
    {
        $post = Yii::$app->request->post();
        $firebase_id = $post['firebase_id'];
        $user = User::find()->where(['firebase_id' => $firebase_id])->one();

        if (!$user) {
            $time = time();
            $user = new User();
            $user->firebase_id = $firebase_id;
            $user->username = 'u' . $time;
            $user->email = 'e' . $time . '@random.kg';
            $user->phone = $post['phone'];
            $user->confirmed_at = $time;
            $user->flags = 111;
            $user->password_hash = Yii::$app->security->generatePasswordHash($time);
            $user->auth_key = Yii::$app->security->generateRandomString();
            if ($user->save()) {
                $profile = Profile::find()->where(['user_id' => $user->id])->one();
                if (!$profile) {
                    $profile = new Profile();
                    $profile->user_id = $user->id;
                }
                $profile->save();
            } else {
                return ['error' => $user->errors];
            }
        }
        return [
            'id' => $user->id,
            'auth_key' => $user->auth_key,
        ];
    }
}
