<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\models\Presentation;

class PresentationController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSend()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $title = "Новая заявка на презентацию";
        $text = "Lorem ipsum dolor sit amet, the quick brown fox jump over the lazy dog";

        $name = Yii::$app->request->post('fullname');
        $phone = Yii::$app->request->post('phone');
        $email = Yii::$app->request->post('email');

        Yii::$app->mailer->compose('layouts/presentation', ['subject' => $title, 'name' => $name,'phone'=>$phone,'email'=>$email])
            ->setBcc('damirbek@gmail.com')
            ->setFrom(['elitkacrm@gmail.com' => 'Elitka.kg'])
            ->setSubject($title)
            ->send();
        return "Ваш заказ звонка принят! Мы свяжемся с вами в ближайшее время!";
    }

}
