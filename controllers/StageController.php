<?php

namespace app\controllers;

use Yii;
use app\models\Stage;
use app\models\StageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;

/**
 * StageController implements the CRUD actions for Stage model.
 */
class StageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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
                            $model = Stage::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->building->object->company->owner_id  == Yii::$app->user->identity->id;
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
                            $model = Stage::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->building->object->company_id == Yii::$app->user->identity->company_id;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Stage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionOwn($id)
    {
        $searchModel = new StageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stage model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Stage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Stage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Stage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Stage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Stage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Stage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stage::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
