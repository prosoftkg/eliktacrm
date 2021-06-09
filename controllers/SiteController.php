<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Selection;
use app\models\Apartment;
use app\models\User;
use yii\db\Query;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionIntro()
    {
        if (!Yii::$app->user->isGuest) {
            $this->redirect(['/user/profile', 'id' => Yii::$app->user->id]);
        } else {
            $this->layout = 'introPage';
            return $this->render('intro');
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    /*     public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    } */

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionSelection()
    {
        $floor = '';
        $object = '';
        $room_amount = '';
        $area = '';
        $price = '';
        $queryWord = '';
        $area = Yii::$app->request->post('area');

        Apartment::find()->joinWith(['plan a'], true, 'INNER JOIN')->where(['a.category' => $area])->all();
        return $this->render('selectionResult', []);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionImgDelete($id, $model_name)
    {
        $key = Yii::$app->request->post('key');
        $webroot = Yii::getAlias('@webroot');
        if (is_dir($dir = $webroot . "/images/{$model_name}/" . $id)) {
            if (is_file($dir . '/' . $key)) {
                @unlink($dir . '/' . $key);
                //@unlink($dir . '/m_' . $key);
                @unlink($dir . '/s_' . $key);
                $dao = Yii::$app->db;
                $row = $dao->createCommand("SELECT img FROM {$model_name} WHERE id='{$id}'")->queryOne();
                $exp = explode(';', $row['img']);
                $newSet = [];
                foreach ($exp as $img) {
                    if ($img != $key) {
                        $newSet[] = $img;
                    }
                }
                $newSetStr = implode(';', $newSet);
                Yii::$app->db->createCommand("UPDATE {$model_name} SET img='{$newSetStr}' WHERE id='{$id}'")->execute();
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return true;
    }

    public function actionImgSort()
    {
        $request = Yii::$app->request;
        $model = $request->post('model_name');
        $id = $request->post('model_id');
        $webroot = Yii::getAlias('@webroot');
        $dir = $webroot . "/images/{$model}/" . $id;
        $images = [];
        if ($stacks = $request->post('stack')) {
            foreach ($stacks as $key => $stack) {
                if (is_file($file = $dir . '/' . $stack['key'])) {
                    $images[] = $stack['key'];
                }
            }
        }
        if ($images) {
            $images_str = implode(';', $images);
            $dao = Yii::$app->db;
            $dao->createCommand("UPDATE {$model} SET img='{$images_str}' WHERE id='{$id}'")->execute();
        }
    }

    protected function testNotif()
    {
        //token for user 75
        $token = 'dyHA0BNOR76J_YoifzGrQ8:APA91bG4VVp9VkrLsj6KBXQ8bvzzNTEDENzBa9uPZDwUCxzhvVvW__Kr5kTmpCIyXV6W07yeAPkN4BNQ2Tog_aLZj_KSJ-wK725ETtaH-kPgXvP5xs_8whZUTdqIjmJfWVbLNUrFHTD_';
        $params = [
            'title' => 'Новые квартиры на вашу подписку',
            'body' => '2,5 этаж Комфорт+, Бизнес 2021 Роял Констракшн, Оомат-Строй  от $125 до $963 за м²',
            'params' => [
                'sub_id' => 41,
                'suburl' => '&price_per_sq=1&price_min=125&price_max=963&floor=2,5&comfort_class=20,30&due_year=2021&company_id=3,2'
            ],
        ];
        User::pushNotification($token, $params);
    }

    public function actionRun()
    {
        $this->testNotif();
    }

    public function actionPrivacy()
    {
        return $this->render('privacy');
    }
}
