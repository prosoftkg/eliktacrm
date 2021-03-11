<?php

namespace app\controllers;

use Yii;
use app\models\Objects;
use app\models\Company;
use app\models\ObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use yii\web\HttpException;


/**
 * ObjectController implements the CRUD actions for Object model.
 */
class ObjectController extends Controller
{
    /**
     * @inheritdoc
     */
    function behaviors()
    {
        return [
            'roleAccess' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['admin']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'own'],
                        'roles' => ['admin', 'owner']
                    ],

                    [
                        'allow' => true,
                        'actions' => ['own'],
                        'roles' => ['manager']
                    ],

                    [
                        'allow' => true,
                        'actions' => ['view', 'update'],
                        'roles' => ['owner'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin) {
                                return true;
                            }
                            $model = Objects::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->company->owner_id == Yii::$app->user->identity->id;
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                        'actions' => ['view'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin) {
                                return true;
                            }
                            $model = Objects::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->company->id == Yii::$app->user->identity->company_id;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Object models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ObjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionList()
    {
        $searchModel = new ObjectSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->query->where(['owner_id' => Yii::$app->user->identity->id])->orWhere(['id' => Yii::$app->user->identity->company_id]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOwn()
    {
        $searchModel = new ObjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        //$company  = Company::find()->where(['owner_id' => Yii::$app->user->id])->one();

        //$dataProvider->query->joinWith(['company'])->andFilterWhere(['company.owner_id' => Yii::$app->user->id]);

        return $this->render('own', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'company'=>$company->id
        ]);
    }

    /**
     * Displays a single Object model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Object model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* if (!Company::find()->andFilterWhere(['owner_id' => Yii::$app->user->identity->id, 'id' => $company])->count()) {
            throw new ForbiddenHttpException('Вы не имеете права добавлять строение для данной компании');
        } */
        $model = new Objects();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                var_dump($model->errors);
            }
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing Object model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Object model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Object model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Object the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Objects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
