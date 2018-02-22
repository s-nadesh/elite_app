<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\UserTypes;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class UsertypesController extends ActiveController {

    public $modelClass = 'common\models\UserTypes';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['index'],
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        //Access - After Login, Role wise access 
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index'],
            'rules' => [
                    [
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

//    Override rest default actions
    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex() {
        $user_types = UserTypes::find()
                ->select('user_type_id, type_name,email_app_login')
                ->visibleSite()
                ->status()
                ->active()
                ->all();
        
        return [
            'success' => true,
            'message' => 'Success',
            'data' => $user_types
        ];
    }

}
