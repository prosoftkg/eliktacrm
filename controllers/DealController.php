<?php

namespace app\controllers;

use Yii;
use app\models\Deal;
use app\models\DealSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

/**
 * DealController implements the CRUD actions for Deal model.
 */
class DealController extends Controller
{
    /**
     * @inheritdoc
     */
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
                        'actions' => ['index'],
                        'roles' => ['owner', 'manager'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['update', 'view', 'delete'],
                        'roles' => ['owner'],
                        'matchCallback' => function ($rule, $action) {
                                $model = Deal::findOne(['id' => Yii::$app->request->get('id')]);
                                return isset($model->company) && $model->company->owner_id == Yii::$app->user->id;
                            }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['update', 'view', 'delete'],
                        'roles' => ['manager'],
                        'matchCallback' => function ($rule, $action) {
                                $model = Deal::findOne(['id' => Yii::$app->request->get('id')]);
                                return isset($model->manager) && $model->manager == Yii::$app->user->id;
                            }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Deal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $searchModel = new DealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($auth->getAssignment('owner', Yii::$app->user->id))
            $dataProvider->query->joinWith(['company'])
                ->andFilterWhere(['company.owner_id'=>Yii::$app->user->id]);
        else if ($auth->getAssignment('manager', Yii::$app->user->id))
            $dataProvider->query
                ->andFilterWhere(['manager'=>Yii::$app->user->id]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Deal model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Deal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Deal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Deal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Deal model.
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
     * Finds the Deal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Deal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
