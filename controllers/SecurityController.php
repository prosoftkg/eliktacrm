<?php
namespace app\controllers;
use Yii;
use dektrium\user\models\LoginForm;
use dektrium\user\controllers\SecurityController as BaseSecurityController;

/**
 * Class SecurityController
 * @package app\controllers
 */
class SecurityController extends BaseSecurityController
{

    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],

                'rules' => [

                    [
                        'allow' => true,
                        'roles' => [''],
                        'actions' => ['update-profile'],
                        'matchCallback' => function ($rule, $action)
                        {
                            $model = User::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->id == Yii::$app->user->identity->id;
                        }
                    ],
                ],
            ]
        ];
    }*/

    public $layout;

    /** @inheritdoc */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['verbs']);
        return $behaviors;
    }


    public function actionLogin()
    {
        $this->layout = "@app/views/layouts/login";
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::createObject(LoginForm::className());
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);
        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            if(!Yii::$app->user->identity->isAdmin){
                return $this->redirect(['/user/profile', 'id' => Yii::$app->user->id]);
            }
            else{
                $this->goHome();
            }
            //return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }

    public function actionLogout()
    {
        $event = $this->getUserEvent(\Yii::$app->user->identity);

        $this->trigger(self::EVENT_BEFORE_LOGOUT, $event);

        \Yii::$app->getUser()->logout();

        $this->trigger(self::EVENT_AFTER_LOGOUT, $event);
        $this->redirect(['/user/login']);
    }
}