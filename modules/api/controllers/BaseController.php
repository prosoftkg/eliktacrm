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
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors'  => [
                'Origin'                           => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age'           => 3600,
            ],
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }
}
