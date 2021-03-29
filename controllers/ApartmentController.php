<?php

namespace app\controllers;

use Yii;
use app\models\Apartment;
use app\models\ApartmentSearch;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\ActiveQuery;
use app\models\Client;
use app\models\Plan;
use app\models\Book;
use app\models\Sold;
use app\models\Deal;
use yii\web\Response;
use dektrium\user\models\User;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

/**
 * ApartmentController implements the CRUD actions for Apartment model.
 */
class ApartmentController extends Controller
{
    /**
     * @inheritdoc
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
        ];
    }

    /**
     * Lists all Apartment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $searchModel = new ApartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Apartment model.
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
     * Creates a new Apartment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Apartment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Apartment model.
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

    public function actionAttach()
    {
        $plan = Yii::$app->request->post('plan');
        Apartment::updateAll([
            'plan_id' => Yii::$app->request->post('plan')
        ], [
            'id' => Json::decode(Yii::$app->request->post('flats'), true)
        ]);
        //        Yii::$app->db->createCommand("UPDATE apartment SET plan_id='$plan' WHERE id IN (".$array.")")->execute();
    }


    /* public function actionStatus()
     {
         //$entry = Yii::$app->request->post('entry');
         $building = Yii::$app->request->post('building');
         $number = Yii::$app->request->post('number');
         $status = Yii::$app->request->post('status');

         //$query = Apartment::find()->where(['number' => $number, 'building_id' => $building])->one();
         Yii::$app->db->createCommand()
             ->update('{{%apartment}}', ['status' => $status], ['number' => $number, 'building_id' => $building])->execute();

     }*/


    /**
     * Deletes an existing Apartment model.
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
     * Finds the Apartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apartment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBook($id)
    {
        $deal = new Deal();
        $clientId = Yii::$app->request->post('Client')['client_name'];
        if (Client::find()->where(['id' => $clientId])->count()) {
            $client = Client::find()->where(['id' => $clientId])->one();
            if (null == $client) {
                $client = new Client();
            }
        } else
            $client = new Client();
        $request = Yii::$app->getRequest();
        if ($request->isPost && $deal->load($request->post()) && $client->load($request->post())) {
            $companyId = Yii::$app->request->post('companyId');
            $objectId = Yii::$app->request->post('objectId');
            Yii::$app->response->format = Response::FORMAT_JSON;

            $deal->apartment_id = $id;
            $deal->status = Deal::STATUS_DEAL_BOOKED;
            $deal->manager = Yii::$app->user->id;
            $deal->company_id = $companyId;
            $deal->object_id = $objectId;

            $client->apartment_id = $id;
            $client->company_id = $companyId;
            $client->save();

            if ($clientId)
                $deal->client_id = $clientId;
            else
                $deal->client_id = $client->id;
            $deal->save();
            Yii::$app->db->createCommand()
                ->update('{{%apartment}}', ['status' => Apartment::STATUS_BOOKED, 'client' => $client->id, 'manager' => Yii::$app->user->id], ['id' => $id])->execute();
            return true;
        } else {
            return $this->renderAjax('book', [
                'deal' => $deal,
                'client' => $client
            ]);
        }
    }

    public function actionPlan()
    {
        $model = new Plan();
        $request = Yii::$app->getRequest();
        if ($request->isPost) {
            $plan_id = Yii::$app->request->post('plan');
            $object_id = Yii::$app->request->post('object_id');
            $dao = Yii::$app->db;
            $area = $dao->createCommand("SELECT area FROM `plan` WHERE id={$plan_id}")->queryScalar();
            $price = $dao->createCommand("SELECT id,base_dollar_price,base_som_price FROM `object` WHERE id={$object_id}")->queryOne();
            $dollar_price = $price['base_dollar_price'] * $area;
            $som_price = $price['base_som_price'] * $area;
            Apartment::updateAll([
                'plan_id' => $plan_id,
                'dollar_price' => $dollar_price,
                'som_price' => $som_price
            ], [
                'id' => Json::decode(Yii::$app->request->post('flats'), true)
            ]);
        } else {
            return $this->renderAjax('plan', [
                'model' => $model,
            ]);
        }
    }

    public function actionSold($id)
    {
        if (Deal::find()->where(['apartment_id' => $id])->count()) {
            $deal = Deal::find()->where(['apartment_id' => $id])->one();
            if (null == $deal) {
                $deal = new Deal();
            }
        } else
            $deal = new Deal();
        $request = Yii::$app->getRequest();

        if (Client::find()->where(['id' => $deal->client_id])->one()) {
            $client = Client::find()->where(['id' => $deal->client_id])->one();

            if (null == $client) {
                $client = new Client();
            }
        } else {
            $client = new Client();
        }

        if ($request->isPost && $deal->load($request->post()) && $client->load($request->post())) {
            $companyId = Yii::$app->request->post('companyId');
            $objectId = Yii::$app->request->post('objectId');
            Yii::$app->response->format = Response::FORMAT_JSON;
            $client->apartment_id = $id;
            $client->company_id = $companyId;
            $client->save();

            if (Yii::$app->request->post('Client')['client_name'])
                $clientId = Yii::$app->request->post('Client')['client_name'];
            else
                $clientId = $client->id;

            $deal->apartment_id = $id;
            $deal->status = Deal::STATUS_DEAL_SOLD;
            $deal->manager = Yii::$app->user->id;
            $deal->company_id = $companyId;
            $deal->object_id = $objectId;
            $deal->client_id = $clientId;
            $deal->save();
            Yii::$app->db->createCommand()->update('{{%apartment}}', ['status' => Apartment::STATUS_SOLD, 'client' => $clientId, 'manager' => Yii::$app->user->id], ['id' => $id])->execute();
            return "form";
        } else {
            return $this->renderAjax('sold', [
                'deal' => $deal,
                'client' => $client,
                'apartment_id' => $id
            ]);
        }
    }

    public function actionReserve()
    {
        $plan = Yii::$app->request->post('plan');
        Apartment::updateAll([
            'status' => Apartment::STATUS_RESERVED
        ], [
            'id' => Json::decode(Yii::$app->request->post('flats'), true)
        ]);
        //        Yii::$app->db->createCommand("UPDATE apartment SET plan_id='$plan' WHERE id IN (".$array.")")->execute();
    }

    public function actionReturn()
    {
        Apartment::updateAll([
            'status' => Apartment::STATUS_RETURN,
            'client' => 0,
            'manager' => 0,
        ], [
            'id' => Json::decode(Yii::$app->request->post('flats'), true)
        ]);
        Deal::deleteAll(['apartment_id' => Json::decode(Yii::$app->request->post('flats'))]);
    }

    public function actionData()
    {
        $number = Yii::$app->request->post('number');
        $apartment = Apartment::find()->where(['id' => $number])->one();
        //$manager = User::find()->where(['id' => $apartment->manager])->one();
        $client = $apartment->clientData;
        $manager = $apartment->managerData;
        $row = "";
        if ($manager) {
            $row .= "
                    <div class='grey_row row_label'>Менеджер</div>
                    <div class='grey_row row_data'>" . $manager->username . "</div>
                    <div class='grey_row row_label clear'>Клиент</div>
                    <div class='grey_row row_data'>" . $client->fullname . "</div>";
        }
        if ($apartment->plan) {
            $row .= "<div class='dialogue_img'>" . Html::img($apartment->plan->getThumbFile()) . "</div>";
        }
        return $row;
    }

    /**
     * Set new price to given apartments
     * @return mixed
     */
    public function actionPrice()
    {
        /** @var app\models\forms\PriceCorrector $mdlPriceCorrector */
        $mdlPriceCorrector = Yii::createObject([
            'class' => 'app\models\forms\PriceCorrector',
        ]);
        if ($mdlPriceCorrector->load(Yii::$app->request->post()) && $mdlPriceCorrector->setNewPrices()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'success' => true,
            ];
        }
        return $this->renderAjax('change_price', [
            'mdlForm' => $mdlPriceCorrector
        ]);
    }

    public function actionLoadproposal($id, $floor)
    {
        $mdlProposal = Yii::createObject([
            'class' => 'app\models\forms\Proposal',
        ]);
        $mdlApartment = Apartment::findOne($id);
        return $this->renderAjax('proposal', [
            'mdlProposal' => $mdlProposal,
            'mdlApartment' => $mdlApartment,
            'floor' => $floor,
        ]);
    }

    public function actionCreateprop()
    {
        $mdlProposal = Yii::createObject([
            'class' => 'app\models\forms\Proposal',
        ]);
        if ($mdlProposal->load(Yii::$app->request->post())) {
            \Yii::$app->session->set('data', $mdlProposal);
            $this->redirect('proposal');
        }
    }

    public function actionProposal()
    {
        return $this->render("proposal-view");
    }


    public function actionSelection()
    {
        $model = new Apartment();
        if ($model->load(Yii::$app->request->post())) {
            $room_amount = Yii::$app->request->post('Apartment')['room_amount'];

            $area = Yii::$app->request->post('area');
            $area = explode(",", $area);
            $area_min = floatval($area[0]);
            $area_max = floatval($area[1]);

            $floor = Yii::$app->request->post('floor');
            $floor = explode(",", $floor);
            $floor_min = (int)$floor[0];
            $floor_max = (int)$floor[1];

            $price_min = Yii::$app->request->post('price_min');
            $price_max = Yii::$app->request->post('price_max');


            $res = Apartment::find()
                ->andFilterWhere(['>=', 'floor', $floor_min])
                ->andFilterWhere(['<=', 'floor', $floor_max])
                ->joinWith(['object o'], true, 'INNER JOIN')
                ->andFilterWhere(['o.company_id' => Yii::$app->user->identity->company_id])
                ->andFilterWhere(['>=', 'o.base_dollar_price', $price_min])
                ->andFilterWhere(['<=', 'o.base_dollar_price', $price_max])
                ->joinWith(['plan p'], true, 'INNER JOIN')
                ->andFilterWhere(['p.room_count' => $room_amount])
                ->andFilterWhere(['>=', 'p.area', $area_min])
                ->andFilterWhere(['<=', 'p.area', $area_max])
                ->all();
            return $this->render('selectionResult', ['res' => $res]);
        } else {
            return $this->render('_selection', [
                'model' => $model,
            ]);
        }
    }


    public function actionSelector()
    {
        $searchModel = new ApartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $price_min_val = "От";
        $price_max_val = "До";

        $area_min = "От";
        $area_max = "До";

        $floor_range = '1,5';
        $room_range = '1,3';

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'price_min_val' => $price_min_val,
            'price_max_val' => $price_max_val,
            'area_min' => $area_min,
            'area_max' => $area_max,
            'floor_range' => $floor_range,
            'room_range' => $room_range,
        ]);
    }
}
