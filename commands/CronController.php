<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Assignment;
use app\models\Deal;
use app\models\Payment;

/**
 * Test controller
 */
class CronController extends Controller
{

    public function actionBookExpiring()
    {
        $bookings = Deal::findBySql('SELECT * FROM `deal` WHERE DATE(`date_to`) = DATE(NOW() + INTERVAL 3 DAY) AND status=1')->all();
        foreach ($bookings as $booking) {
            $assignment = new Assignment();
            $assignment->type = Assignment::ASSIGNMENT_TYPE_BOOKING;
            $assignment->priority = Assignment::ASSIGNMENT_PRIORITY_URGENT;
            $assignment->status = Assignment::ASSIGNMENT_STATUS_OPENED;
            $assignment->date_from = date('Y-m-d');
            $assignment->date_to = $booking->date_to;
            $assignment->description = "Истекает срок брони квартиры #" . $booking->apartment->number . ". Здание " . $booking->apartment->building->title . ".Объект " . $booking->object->title;
            $assignment->user_id = $booking->manager;
            $assignment->auto_task = "expiring";
            $assignment->apartment_id = $booking->apartment->id;
            $assignment->save();
        }
    }

    public function actionBookExpired()
    {
        $bookings = Deal::findBySql('SELECT * FROM `deal` WHERE DATE(`date_to`) = DATE(NOW() - INTERVAL 1 DAY) AND status=1')->all();
        foreach ($bookings as $booking) {
            $assignment = Assignment::find()->where(['apartment_id' => $booking->apartment->id, 'auto_task' => 'expiring'])->one();
            if ($assignment) {
                $assignment->priority = Assignment::ASSIGNMENT_PRIORITY_STANDARD;
                $assignment->status = Assignment::ASSIGNMENT_STATUS_CLOSED;
                $assignment->date_from = date('Y-m-d');
                $assignment->date_to = date('Y-m-d');
                $assignment->description = "Истек срок брони квартиры #" . $booking->apartment->number . ". Здание " . $booking->apartment->building->title . ". Объект " . $booking->object->title;
                $assignment->auto_task = "expired";
                $assignment->save();
                $booking->delete();
            }
        }
    }

    public function actionPaymentReminder()
    {
        $payments = Payment::findBySql('SELECT * FROM `payment` WHERE DATE(`pay_date`) = DATE(NOW() + INTERVAL 3 DAY) AND status=0')->all();
        foreach ($payments as $payment) {
            $assignment = new Assignment();
            $assignment->type = Assignment::ASSIGNMENT_TYPE_PAYMENT;
            $assignment->priority = Assignment::ASSIGNMENT_PRIORITY_URGENT;
            $assignment->status = Assignment::ASSIGNMENT_STATUS_OPENED;
            $assignment->date_from = date('Y-m-d');
            $assignment->date_to = $payment->pay_date;
            $assignment->description = "Приближается крайний срок оплаты следующей сделки: Кваритра #" . $payment->apartment->number . ". Здание " . $payment->apartment->building->title . ". Объект " . $payment->apartment->building->object->title;
            $assignment->user_id = $payment->apartment->manager;
            $assignment->auto_task = "expiring";
            $assignment->apartment_id = $payment->apartment->id;
            $assignment->save();
        }
    }

    public function actionPaymentDelay()
    {
        $payments = Payment::findBySql('SELECT * FROM `payment` WHERE DATE(`pay_date`) = DATE(NOW() - INTERVAL 1 DAY) AND status=0')->all();
        foreach ($payments as $payment) {
            $assignment = Assignment::find()->where(['apartment_id'=>$payment->apartment->id])->one();
            $assignment->date_to = date('Y-m-d');
            $assignment->description = "Истек крайний срок оплаты следующей сделки: Кваритра #" . $payment->apartment->number . ". Здание " . $payment->apartment->building->title . ". Объект " . $payment->apartment->building->object->title;
            $assignment->auto_task = "expired";
            $assignment->save();
        }
    }
}