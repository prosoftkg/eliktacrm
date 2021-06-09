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

        $allowed = $this->saveFcm($user->id, $post);
        return [
            'id' => $user->id,
            'name' => $user->profile->name,
            'auth_key' => $user->auth_key,
            'allow_push' => $allowed
        ];
    }

    protected function saveFcm($user_id, $post, $shouldDelete = true)
    {
        if (!empty($post['fcm_token']) && !empty($post['device_id'])) {
            $fcm_token = $post['fcm_token'];
            $device_id = $post['device_id'];
            $dao = Yii::$app->db;
            $sql = "SELECT * FROM `fcm_token` WHERE user_id={$user_id} AND device_id='{$device_id}' AND token='{$fcm_token}'";
            $row = $dao->createCommand($sql)->queryOne();
            if (!$row) {
                if ($shouldDelete) {
                    //delete prev
                    $dao->createCommand()->delete('fcm_token', ['user_id' => $user_id, 'device_id' => $device_id])->execute();
                }
                $dao->createCommand()->insert('fcm_token', ['user_id' => $user_id, 'device_id' => $device_id, 'token' => $fcm_token, 'allowed' => 1, 'created_at' => time()])->execute();
            } else {
                return $row['allowed'];
            }
        }
        return 1;
    }

    public function actionEdit()
    {
        $user_id = Yii::$app->user->id;
        $post = Yii::$app->request->post();
        $dao = Yii::$app->db;
        if (isset($post['name'])) {
            $command = $dao->createCommand()->update('profile', ['name' => $post['name']], ['user_id' => $user_id]);
        } else if (isset($post['allow_push'])) {
            $command = $dao->createCommand()->update('fcm_token', ['allowed' => $post['allow_push']], ['user_id' => $user_id]);
            $this->saveFcm($user_id, $post, false);
        }
        if (isset($command) && $command->execute()) {
            return true;
        }
        return false;
    }
}
