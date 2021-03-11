<?php

namespace app\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "assignment".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $priority
 * @property integer $status
 * @property string $date_from
 * @property string $date_to
 * @property string $description
 */
class Assignment extends \yii\db\ActiveRecord
{
    const ASSIGNMENT_TYPE_CALL = 1;
    const ASSIGNMENT_TYPE_BOOKING = 2;
    const ASSIGNMENT_TYPE_MEETING = 3;
    const ASSIGNMENT_TYPE_DEMONSTRATION = 4;
    const ASSIGNMENT_TYPE_LETTER = 5;
    const ASSIGNMENT_TYPE_TASK = 6;
    const ASSIGNMENT_TYPE_PAYMENT = 7;

    const ASSIGNMENT_STATUS_OPENED = 1;
    const ASSIGNMENT_STATUS_CLOSED = 2;

    const ASSIGNMENT_PRIORITY_URGENT = 1;
    const ASSIGNMENT_PRIORITY_STANDARD = 2;

    public $periods;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'priority', 'status', 'date_from', 'date_to', 'description'], 'required'],
            [['type', 'priority', 'status'], 'integer'],
            [['date_from', 'date_to', 'user_id','auto_task','apartment_id','priority'], 'safe'],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Тип задачи'),
            'priority' => Yii::t('app', 'Приоритет'),
            'status' => Yii::t('app', 'Статус'),
            'date_from' => Yii::t('app', 'От'),
            'date_to' => Yii::t('app', 'До'),
            'periods' => Yii::t('app', 'Сроки выполнения'),
            'description' => Yii::t('app', 'Задача'),
            'user_id' => Yii::t('app', 'Менеджеры'),
        ];
    }

    public function assignmentType($type)
    {
        switch ($type) {
            case Assignment::ASSIGNMENT_TYPE_CALL:
                $title = "Звонок клиенту";
                break;

            case Assignment::ASSIGNMENT_TYPE_BOOKING :
                $title = "Истечение срока брони";
                break;

            case Assignment::ASSIGNMENT_TYPE_DEMONSTRATION :
                $title = "Демонстрация";
                break;

            case Assignment::ASSIGNMENT_TYPE_MEETING :
                $title = "Встреча с клиентами";
                break;

            case Assignment::ASSIGNMENT_TYPE_LETTER :
                $title = "Письмо клиенту";
                break;

            case Assignment::ASSIGNMENT_TYPE_TASK :
                $title = "Задание";
                break;
            case Assignment::ASSIGNMENT_TYPE_PAYMENT :
                $title = "Напоминание о платежах";
                break;

            default:
                $title = "Другое";
        }
        return $title;
    }

    public function assignmentStatus($status)
    {
        switch ($status) {
            case Assignment::ASSIGNMENT_STATUS_OPENED:
                $status = "Открыт";
                break;

            case Assignment::ASSIGNMENT_STATUS_CLOSED :
                $status = "Сделано";
                break;

            default:
                $status = "Другое";
        }
        return $status;
    }

    public function assignmentPriority($priority)
    {
        switch ($priority) {
            case Assignment::ASSIGNMENT_PRIORITY_URGENT:
                $priority = "Срочный";
                break;

            case Assignment::ASSIGNMENT_PRIORITY_STANDART :
                $priority = "Стандартный";
                break;

            default:
                $priority = "Другое";
        }
        return $priority;
    }


}
