<?php

namespace app\controllers;

use app\models\Apartment;
use app\models\Objects;
use yii\web\ForbiddenHttpException;
use Yii;
use app\models\Building;
use app\models\Book;
use app\models\BuildingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Json;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use yii\web\HttpException;
use yii\db\ActiveQuery;

/**
 * BuildingController implements the CRUD actions for Building model.
 */
class BuildingController extends Controller
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
                        'actions' => ['create', 'view', 'update', 'own', 'index'],
                        'roles' => ['admin']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'own'],
                        'roles' => ['admin', 'owner', 'manager']
                    ],


                    [
                        'allow' => true,
                        'roles' => ['manager'],
                        'actions' => ['view'],
                        'matchCallback' => function ($rule, $action) {
                            $model = Building::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->object->company_id == Yii::$app->user->identity->company_id;
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['owner'],
                        'actions' => ['view', 'update'],
                        'matchCallback' => function ($rule, $action) {
                            $model = Building::findOne(['id' => Yii::$app->request->get('id')]);
                            return $model->object->company->owner_id == Yii::$app->user->identity->id;
                        }
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all Building models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BuildingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOwn($id)
    {
        if (!Objects::find()->joinWith(['company'])->andFilterWhere(['company.owner_id' => Yii::$app->user->id, 'object.id' => $id])->count()) {
            throw new ForbiddenHttpException('У вас нет доступа просмотривать данную страницу');
        }
        $searchModel = new BuildingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Building model.
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

    function actionUserList($name)
    {
        $query = new ActiveQuery(Object::className());
        return [
            'results' => $query->select([
                'id',
                'title',
                'CONCAT_WS(" ", title) as text',
            ])
                ->andFilterWhere([
                    'or',
                    ['like', 'title', $name],
                ])
                ->asArray()
                ->limit(10)
                ->all(),
        ];
    }

    /**
     * Creates a new Building model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($object)
    {
        if (!Objects::find()->joinWith(['company'])->andFilterWhere(['company.owner_id' => Yii::$app->user->id, 'object.id' => $object])->count()) {
            throw new ForbiddenHttpException('Вы не имеете права добавлять строение для данной компании');
        }
        $model = new Building();
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object' => $object,
            ]);
        }
    }

    /**
     * Updates an existing Building model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            //$model->owner_id = Yii::$app->user->id;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Building model.
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
     * Finds the Building model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Building the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Building::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
