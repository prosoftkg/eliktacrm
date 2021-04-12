<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property int $created_at
 * @property string $subject
 * @property string $subject_link
 * @property int $archive
 *
 * @property User $receiver
 * @property User $sender
 */
class Chat extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            [['sender_id', 'receiver_id', 'created_at', 'updated_at', 'archive'], 'integer'],
            [['subject', 'subject_link'], 'required'],
            [['subject'], 'string', 'max' => 255],
            [['subject_link'], 'string', 'max' => 20],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sender_id' => Yii::t('app', 'Sender'),
            'receiver_id' => Yii::t('app', 'Receiver'),
            'created_at' => Yii::t('app', 'Created At'),
            'subject' => Yii::t('app', 'Subject'),
            'subject_link' => Yii::t('app', 'Subject Link'),
            'archive' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }

    public function getChatlines()
    {
        return $this->hasMany(Chatline::className(), ['chat_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    public function sendEmail()
    {
        if ($this->receiver->email) {
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'msgReceived-html', 'text' => 'msgReceived-text'],
                    ['chat' => $this]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                ->setTo($this->receiver->email)
                ->setSubject('Вам сообщение!')
                ->send();
        }
    }


    //for rest api
    public function fields()
    {
        $action = Yii::$app->controller->action->id;
        $user_id = Yii::$app->user->id;
        $fields = parent::fields();

        if ($action == 'list') {
            $fields['unreads'] = function ($model) use ($user_id) {
                $dao = Yii::$app->db;
                $posts = $dao->createCommand("SELECT id AS unreads FROM chatline WHERE chat_id='{$model->id}' 
AND is_read=0 AND receiver_id='{$user_id}'")->queryAll();
                return count($posts);
            };
        }

        $fields['name'] = function ($model) use ($user_id) {
            if ($user_id == $model->sender_id) {
                return $model->receiver->profile->name;
            } else {
                return $model->sender->profile->name;
            }
        };

        return $fields;
    }

    public static function send($post, $user_id)
    {
        if (!empty($post['view']) && !empty($post['view_id'])) {
            $link = $post['view'] . '/' . $post['view_id'];
        } else if (!empty($post['subject_link'])) {
            $link = $post['subject_link'];
        } else {
            $link = '';
        }
        if (!empty($post['subject'])) {
            $subject = $post['subject'];
        } else {
            $subject = '';
        }

        if (!empty($post['text']) && !empty($post['receiver_id'])) {
            if (!empty($post['chat_id'])) {
                $chat_id = $post['chat_id'];
            } else {
                $chat = new Chat();
                $chat->sender_id = $user_id;
                $chat->receiver_id = $post['receiver_id'];
                $chat->subject = $subject;
                $chat->subject_link = $link;
                $chat->save();
                $chat_id = $chat->id;

                //$chat->sendEmail();
            }

            if ($chat_id) {
                $dao = Yii::$app->db;
                $line = new Chatline();
                $line->chat_id = $chat_id;
                $line->sender_id = $user_id;
                $line->receiver_id = $post['receiver_id'];
                $line->is_read = 0;
                $line->sent_at = time();
                $line->text = $post['text'];
                $line->save();
                if (isset($chat) && $chat->archive == 1) {
                    $dao->createCommand("UPDATE chat SET `archive`=null WHERE id='{$chat_id}'")->execute();
                }
            }

            return $chat_id;
        }
        return 0;
    }

    public static function sendWelcome($user_id)
    {
        $dao = Yii::$app->db;
        $post = $dao->createCommand("SELECT * FROM `page` WHERE category='after_register'")->queryOne();
        if ($post) {
            $params = [
                'subject' => $post['title'],
                'text' => $post['text'],
                'receiver_id' => $user_id,
                'subject_link' => 'register',
            ];
            self::send($params, 6);
        }
    }
}
