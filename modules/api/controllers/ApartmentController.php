<?php

namespace app\modules\api\controllers;

use app\models\Apartment;
use Yii;
use app\models\ApartmentSearch;
use app\models\Company;
use app\models\Objects;
use app\models\Fav;

class ApartmentController extends BaseController
{
    public $modelClass = 'app\models\Apartment';

    /**
     * @inheritDoc
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete'], $actions['options']);

        $actions['index'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'prepareDataProvider' => function () {
                $searchModel = new ApartmentSearch();
                return $searchModel->search(Yii::$app->request->queryParams);
            },
        ];

        return $actions;
    }

    public function actionDetail($id)
    {
        $model = Apartment::find()->where(['id' => $id])->one();
        $similars = Apartment::find()->where(['object_id' => $model->object_id, 'plan_id' => $model->plan_id])->andWhere(['<>', 'id', $model->id])->limit(2)->all();
        /* $similar = [];
        foreach ($similars as $sim) {
            $similar[] = ['id' => $sim->id, 'img' => 'images/plan/' . $sim->plan_id . '/' . $sim->plan->img];
        } */
        /*         $stage_images = [];
        if ($stages = $model->building->stages) {
            $last_date = 0;
            foreach ($stages as $stage) {
                if ($stage->date_stage > $last_date) {
                    $last_date = $stage->date_stage;
                }
                $stage_images = array_merge($stage_images, explode(';', $stage->img));
            }
        } */
        return [
            'id' => $model->id,
            'object_description' => $model->object->description,
            'company_info' => $model->object->company->name,
            'rooms_info' => unserialize($model->plan->rooms),
            'similars' => $similars,
            'stages' => $model->building->stages
        ];
    }

    public function actionFilterCount()
    {
        $searchModel = new ApartmentSearch();
        return $searchModel->search(Yii::$app->request->queryParams)->getTotalCount();
    }
    public function actionMapIndex()
    {
        $query = ApartmentSearch::myQuery(Yii::$app->request->queryParams, true);
        $rows = $query->asArray()->all();
        //return $rows;
        $list = [];
        foreach ($rows as $row) {
            $obj = $row['object'];
            if (!isset($list[$obj['id']])) {
                $list[$obj['id']] = [
                    'id' => (int)$obj['id'],
                    'title' => $obj['title'],
                    'due' => $row['building']['due_quarter'] . ' квартал ' . $row['building']['due_year'] . ' г.',
                    'price' => (int)$obj['base_dollar_price'],
                    'lat' => (float)$obj['lat'],
                    'lng' => (float)$obj['lng'],
                ];
            }
        }
        return array_values($list);
    }
    public function actionMapApts()
    {
        $query = ApartmentSearch::myQuery(Yii::$app->request->queryParams);
        return $query->all();
    }
    public function actionCompanies()
    {
        return Company::find()->select(['id', 'name', 'phone'])->orderBy('name ASC')->all();
    }
    public function actionObjects()
    {
        return Objects::find()->select(['id', 'title', 'company_id'])->orderBy('title ASC')->all();
    }
    public function actionFavs()
    {
        $dao = Yii::$app->db;
        $uid = Yii::$app->user->id;
        //return $dao->createCommand("SELECT * FROM `fav` WHERE user_id={$uid}")->queryAll();
        $apt_id_rows = $dao->createCommand("SELECT apartment_id FROM `fav` WHERE user_id={$uid}")->queryAll();
        $apt_ids = [];
        foreach ($apt_id_rows as $aid) {
            $apt_ids[] = $aid['apartment_id'];
        }
        return Apartment::find()->where(['in', 'id', $apt_ids])->all();
    }
    public function actionSubs()
    {
        $dao = Yii::$app->db;
        $uid = Yii::$app->user->id;
        //return $dao->createCommand("SELECT * FROM `fav` WHERE user_id={$uid}")->queryAll();
        $rows = $dao->createCommand("SELECT * FROM `fav` WHERE user_id={$uid} AND apartment_id IS NULL")->queryAll();
        return $rows;
    }

    public function actionFav()
    {
        $post = Yii::$app->request->post();
        $dao = Yii::$app->db;
        $uid = Yii::$app->user->id;

        //bookmark apt
        if (!empty($post['apartment_id'])) {
            $aid = $post['apartment_id'];
            $already = $dao->createCommand("SELECT * FROM `fav` WHERE user_id={$uid} AND apartment_id={$aid}")->queryOne();
            if ($already) {
                $dao->createCommand()->delete('fav', ['apartment_id' => $aid, 'user_id' => $uid])->execute();
                return 0;
            } else {
                $dao->createCommand()->insert('fav', ['apartment_id' => $aid, 'user_id' => $uid])->execute();
                return 1;
            }
        }
        //subscribe to search
        if (!empty($post['url']) && !empty($post['title'])) {
            $model = new Fav();
            $model->url = $post['url'];
            $model->title = $post['title'];
            $model->user_id = $uid;
            $model->save();
            return $model->id;
        }
        return null;
    }

    //delete subscription
    public function actionDeleteFav()
    {
        $id = Yii::$app->request->post('id');
        $uid = Yii::$app->user->id;
        $dao = Yii::$app->db;
        $command = $dao->createCommand()->delete('fav', ['user_id' => $uid, 'id' => $id]);
        if ($command->execute()) {
            return 1;
        }
        return null;
    }
}
