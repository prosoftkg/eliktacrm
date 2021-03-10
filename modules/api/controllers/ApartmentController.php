<?php

namespace app\modules\api\controllers;

use app\models\Apartment;
use Yii;
use app\models\ApartmentSearch;

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
            $similar[] = ['id' => $sim->id, 'img' => $sim->plan->img];
        }
        return [
            'id' => $model->id,
            'object_description' => $model->object->description,
            'company_info' => $model->object->company->name,
            'rooms_info' => unserialize($model->plan->rooms),
            'similar' => $similar
        ];
    }
}
