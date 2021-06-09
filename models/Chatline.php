<?php

namespace app\models;

use Yii;
use frontend\models\Offer;
use yii\helpers\StringHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chatline".
 *
 * @property int $id
 * @property int $chat_id
 * @property int $sender_id
 * @property int $receiver_id
 * @property int $is_read
 * @property string $text
 * @property int $sent_at
 */
class Chatline extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chatline';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            //TimestampBehavior::className(),
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['sent_at'],
                    //ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chat_id', 'sender_id', 'receiver_id', 'is_read', 'sent_at'], 'integer'],
            [['text'], 'required'],
            [['text'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'chat_id' => Yii::t('app', 'Chat ID'),
            'sender_id' => Yii::t('app', 'Sender ID'),
            'receiver_id' => Yii::t('app', 'Receiver ID'),
            'is_read' => Yii::t('app', 'Is Read'),
            'text' => Yii::t('app', 'Text'),
            'sent_at' => Yii::t('app', 'Sent At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChat()
    {
        return $this->hasOne(Chat::className(), ['id' => 'chat_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $dao = Yii::$app->db;
        /*$dao->createCommand()->insert('test', [
            'title' => 'chatline aftersave',
            'number' => 1,
        ])->execute();*/

        if ($this->chat->archive == 1) {
            $dao->createCommand("UPDATE chat SET `archive`=null WHERE id='{$this->chat_id}'")->execute();
        }

        $params = [
            'title' => 'Новое сообщение!',
            'body' => StringHelper::truncate($this->text, 100),
            //'user_id' => $this->receiver_id,
            'params' => [
                'chat_id' => $this->chat_id,
                'sender_id' => $this->sender_id,
                'text' => $this->text,
                'link' => $this->chat->subject_link,
                'subject' => $this->chat->subject
            ],
        ];
        //$notif_id=User::createNotif($params);
        $token_rows = $dao->createCommand("SELECT * FROM fcm_token WHERE user_id='{$this->receiver_id}' AND allowed=1 AND active_chat_id<>'{$this->chat_id}'")->queryAll();
        foreach ($token_rows as $tokenRow) {
            User::pushNotification($tokenRow['token'], $params);
        }

        $time = time();
        $dao->createCommand("UPDATE chat SET `updated_at`='{$time}' WHERE id='{$this->chat_id}'")->execute();
    }
}
