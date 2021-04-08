<?php

namespace app\modules\api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\Chat;
use app\models\Chatline;

class ChatController extends BaseController
{
    public $modelClass = 'app\models\Chat';

    public function actions()
    {
        return [];
    }

    /**
     * @return \yii\web\IdentityInterface|null
     */
    public function actionAdd()
    {
        $response["success"] = false;
        $req = Yii::$app->request;
        $chatId = $req->post('chat_id');
        if ($chatId) {
            $peer = Chat::findOne($chatId);
        } else {
            $peer = Chat::find()->where(['link' => $req->post('link'), 'receiver_id' => $req->post('receiver_id')])->one();
            if (!$peer) {
                $peer = new Chat();
                $peer->receiver_id = $req->post('receiver_id');
                $peer->sender_id = Yii::$app->user->id;
                $peer->subject = $req->post('subject');
                $peer->subject_link = $req->post('link');
                if ($peer->save()) {
                } else {
                    $response["errors"]['chat'] = $peer->errors;
                }
            }
        }
        if (!empty($peer->id)) {
            $msg = new Chatline();
            $msg->text = $req->post('message');
            $msg->receiver_id = $req->post('receiver_id');
            $msg->link('chat', $peer);
            $response["success"] = true;

            if (isset($chat) && $chat->archive == 1) {
                //$dao->createCommand("UPDATE chat SET `archive`=null WHERE id='{$chat_id}'")->execute();
            }
        }

        return $response;
    }

    public function actionLoad()
    {
        $user_id = Yii::$app->user->id;
        $get = Yii::$app->request->get();
        if (!empty($get['chat_id'])) {
            $id = $get['chat_id'];
        } else {
            $id = self::getId($get);
        }
        if ($id) {
            $query = Chatline::find()->where(['chat_id' => $id]);
            $dao = Yii::$app->db;
            $dao->createCommand("UPDATE `chatline` SET is_read=1 WHERE chat_id='{$id}' AND receiver_id='{$user_id}'")->execute();

            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 25,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ]
                ],
            ]);
        }
        return null;
    }

    public function actionViewed()
    {
        $chat_id = Yii::$app->request->post('chat_id');
        $dao = Yii::$app->db;
        $dao->createCommand()->update(
            'chatline',
            ['is_read' => 1],
            ['chat_id' => $chat_id, 'is_read' => null, 'receiver_id' => Yii::$app->user->id]
        )->execute();
    }
    public function actionArchive()
    {
        $chat_id = Yii::$app->request->post('chat_id');
        $dao = Yii::$app->db;

        $dao->createCommand()->update(
            'chat',
            ['archive' => 1],
            ['id' => $chat_id]
        )->execute();
    }

    protected static function getId($get)
    {
        if (isset($get['link']) && isset($get['receiver_id']) && isset($get['sender_id'])) {

            $peer = Chat::find()->select('id')->where([
                'link' => $get['link'],
                'receiver_id' => $get['receiver_id'],
                'sender_id' => $get['sender_id']
            ])->one();
            if ($peer) {
                return $peer->id;
            }
        }
        return null;
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function actionList()
    {
        $id = Yii::$app->user->id;
        $query = Chat::find()->where(['OR', 'sender_id=' . $id, 'receiver_id=' . $id]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }
}
