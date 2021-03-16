<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use \yii\filters\Cors;

class BaseController extends ActiveController
{
    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        /* return ArrayHelper::merge([
            [
                'class' => Cors::className(),
            ],
        ], parent::behaviors()); */

        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }
}
