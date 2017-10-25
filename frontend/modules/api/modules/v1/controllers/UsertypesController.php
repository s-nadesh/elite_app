<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\LoginForm;
use Yii;
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
        return $behaviors;
    }

    public function actionLogin() {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->login()) {
            return [
                'success' => 'true',
                'access_token' => Yii::$app->user->identity->getAuthKey()];
        } else {
            return [
                'success' => 'false',
                'message' => 'Email / Password Combination is wrong',
            ];
        }
    }

}
