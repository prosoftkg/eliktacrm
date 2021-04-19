<?php

namespace app\modules\api\controllers;

use app\models\Apartment;
use Yii;
use app\models\ApartmentSearch;
use app\models\Company;
use app\models\Objects;

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
        $similars = Apartment::find()->where(['object_id' => $model->object_id, 'plan_id' => $model->plan_id])->andWhere(['<>', 'id', $model->id])->limit(6)->all();
        $similar = [];
        foreach ($similars as $sim) {
            $similar[] = ['id' => $sim->id, 'img' => 'images/plan/' . $sim->plan_id . '/' . $sim->plan->img];
        }
        return [
            'id' => $model->id,
            'object_description' => $model->object->description,
            'company_info' => $model->object->company->name,
            'rooms_info' => unserialize($model->plan->rooms),
            'similar' => $similar
        ];
    }

    public function actionFilterCount()
    {
        $searchModel = new ApartmentSearch();
        return $searchModel->search(Yii::$app->request->queryParams)->getTotalCount();
    }
    public function actionCompanies()
    {
        return Company::find()->select(['id', 'name'])->all();
    }
    public function actionObjects()
    {
        return Objects::find()->select(['id', 'title', 'company_id'])->all();
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

    public function actionFav()
    {
        $aid = Yii::$app->request->post('apartment_id');
        $dao = Yii::$app->db;
        $uid = Yii::$app->user->id;
        $already = $dao->createCommand("SELECT * FROM `fav` WHERE user_id={$uid} AND apartment_id={$aid}")->queryOne();
        if ($already) {
            $dao->createCommand()->delete('fav', ['apartment_id' => $aid, 'user_id' => $uid])->execute();
            return 0;
        } else {
            $dao->createCommand()->insert('fav', ['apartment_id' => $aid, 'user_id' => $uid])->execute();
            return 1;
        }
    }
}
