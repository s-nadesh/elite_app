<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends ActiveController {

    public $modelClass = 'common\models\Logins';

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
        $model->login_from = \common\models\Logins::FRONT_LOGIN;
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->login()) {
            return [
                'success' => 'true',
                'access_token' => Yii::$app->user->identity->getAuthKey()
            ];
        } else {
            return [
                'success' => 'false',
                'message' => 'Email / Password Combination is wrong',
            ];
        }
    }

}
