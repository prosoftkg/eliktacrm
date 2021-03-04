<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\Payment;
use app\models\PaymentSearch;
use yii\web\Response;
use yii\helpers\Json;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
                        'actions' => ['index', 'payment', 'status', 'remove','apply'],
                        'roles' => ['admin', 'owner', 'manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'view', 'delete'],
                        'roles' => ['owner'],
                        /*'matchCallback' => function ($rule, $action) {
                                $model = Client::findOne(['id' => Yii::$app->request->get('id')]);
                                return $model->company->owner_id == Yii::$app->user->identity->id;
                            }*/
                    ],
                ],
            ],
        ];
    }

    public function actionPayment()
    {
        $model = new Payment();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model->client_id = Yii::$app->request->post('clientId');
        $model->apartment_id = Yii::$app->request->post('apartmentId');
        $model->sum = Yii::$app->request->post('sum');
        $model->pay_date = Yii::$app->request->post('date');
        if ($model->save()) {
            return true;
        }
    }

    public function actionApply()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $client = Client::find()->where(['id'=>$id])->one();
        $phone = $client->phone;
        $phone2 = $client->phone2;
        /*$date_from = $client->deal->date_from;
        $date_to = $client->deal->date_to;*/
        $passport_num = $client->passport_num;
        $email = $client->email;
        $address = $client->address;
        $birthday = $client->birthday;
        //$refference = $client->deal->refference;
        $dataArr = [$phone,$phone2,$passport_num,$email,$address,$birthday];
        return $dataArr;
    }

    /**
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->joinWith(['company'])
            ->andFilterWhere(['or',
                ['=', 'company.owner_id', Yii::$app->user->id],
            ]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new PaymentSearch();
        $model = $this->findModel($id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere([
                'client_id' => $model->id
            ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStatus()
    {
        $status = Yii::$app->request->post('status');
        $paymentId = Yii::$app->request->post('paymentId');
        $payment = Payment::findOne($paymentId);
        $payment->status = $status;
        $payment->update();
    }

    public function actionRemove()
    {
        $paymentId = Yii::$app->request->post('paymentId');
        $payment = Payment::findOne($paymentId);
        $payment->delete();
        return true;
    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Client model.
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
     * Deletes an existing Client model.
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
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
