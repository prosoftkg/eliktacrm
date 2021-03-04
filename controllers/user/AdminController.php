<?php namespace app\controllers\user;

use Yii;
use dektrium\user\controllers\AdminController as BaseAdminController;

use dektrium\user\filters\AccessRule;
use dektrium\user\models\User;
use dektrium\user\models\UserSearch;
use dektrium\user\models\Profile;
use yii\filters\AccessControl;
use yii\helpers\Url;
use app\models\Company;
use yii\web\ForbiddenHttpException;


class AdminController extends BaseAdminController
{
    public function behaviors()
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
                        'roles' => ['admin'],

                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['update-profile'],
                        'matchCallback' => function ($rule, $action)
                        {
                            $model = User::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->id == Yii::$app->user->identity->id;
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['useradd', 'manager-list', 'block', 'delete'],
                        'roles' => ['owner'],
                        /*'matchCallback' => function ($rule, $action)
                        {
                            $model = User::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->company_id == Yii::$app->user->identity->company_id;
                        }*/
                    ],

                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['owner'],
                        'matchCallback' => function ($rule, $action)
                        {
                            $model = User::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->parent_id == Yii::$app->user->id || $model->id == Yii::$app->user->id;
                        }
                    ],


                    [
                        'allow' => true,
                        'actions' => ['block'],
                        'roles' => ['owner']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['profile'],
                        'roles' => ['owner', 'manager']
                    ],
                ],
            ],
        ];
    }

    public function actionUpdateProfile($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $profile = $user->profile;

        if ($profile == null) {
            $profile = \Yii::createObject(Profile::className());
            $profile->link('user', $user);
        }
        $event = $this->getProfileEvent($profile);

        $this->performAjaxValidation($profile);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

        if ($profile->load(\Yii::$app->request->post()) && $profile->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Profile details have been updated'));
            $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
            return $this->redirect(['/user/' . $id]);
        }

        return $this->render('_profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function actionUseradd($id)
    {
        if (!Company::find()->andFilterWhere(['owner_id' => Yii::$app->user->identity->id, 'id' => $id])->count()) {
            throw new ForbiddenHttpException('Вы не имеете права добавлять менеджера для данной компании');
        }
        /** @var User $user */
        $user = \Yii::createObject([
            'class' => User::className(),
            'scenario' => 'create',
        ]);
        $event = $this->getUserEvent($user);
        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        if ($user->load(\Yii::$app->request->post())) {
            $user->company_id = $id;
            $user->parent_id = Yii::$app->user->id;
            $user->profile = \Yii::createObject([
                'class' => 'app\models\Profile',
                'name' => $user->name,
            ]);
            $user->create();
//            var_dump($user->errors, $user->parent_id);
//            die();
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('manager');
            $auth->assign($authorRole, $user->id);
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'User has been created'));
            $this->trigger(self::EVENT_AFTER_CREATE, $event);
            return $this->redirect(['/user/admin/manager-list']);
        }

        return $this->render('adduser', [
            'user' => $user,
        ]);
    }

    public function actionProfile($userId)
    {
        if (Yii::$app->user->id == $userId || Yii::$app->user->identity->isAdmin) {
            Url::remember('', 'actions-redirect');
            $user = $this->findModel(Yii::$app->user->id);
            $profile = $user->profile;

            if ($profile == null) {
                $profile = \Yii::createObject(Profile::className());
                $profile->link('user', $user);
            }
            $event = $this->getProfileEvent($profile);

            $this->performAjaxValidation($profile);

            $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);

            if ($profile->load(\Yii::$app->request->post()) && $profile->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Profile details have been updated'));
                $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
                return $this->refresh();
            }

            return $this->render('_profile', [
                'user' => $user,
                'profile' => $profile,
            ]);

        } else {
            throw new ForbiddenHttpException('Вы не имеете доступа к данной странице');
        }
    }

    public function actionManagerList()
    {
        Url::remember('', 'actions-redirect');
        $company = '';
        $companyId=null;
        $searchModel = \Yii::createObject(UserSearch::className());
        $company  = Company::find()->where(['owner_id'=>Yii::$app->user->id])->one();
        if($company){$companyId=$company->id;}
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->query->joinWith(['company'])
            ->andFilterWhere(['or',
            ['=', 'company.owner_id', Yii::$app->user->id],
            ['=', 'company.id', Yii::$app->user->identity->company_id]
        ]);

        return $this->render('manager', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'company'=>$companyId
        ]);
    }

    public function actionUpdate($id)
    {
        Url::remember('', 'actions-redirect');
        $user = $this->findModel($id);
        $user->scenario = 'update';
        $event = $this->getUserEvent($user);

        $this->performAjaxValidation($user);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);
        if ($user->load(\Yii::$app->request->post()) && $user->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Account details have been updated'));
            $this->trigger(self::EVENT_AFTER_UPDATE, $event);
            return $this->redirect(['user/view', 'id' => $id]);
        }
        return $this->render('_account', [
            'user' => $user,
        ]);
    }
}
