<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\LoginForm;
use common\models\Logins;
use common\models\ResetPassword;
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
            'only' => ['index', 'changepassword'],
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
        $model->login_from = Logins::FRONT_LOGIN;
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->login()) {
            return [
                'success' => 'true',
                'message' => 'Login successful',
                 'user_id'=> Yii::$app->user->getId(),
                'access_token' => Yii::$app->user->identity->getAuthKey(),
                'test'=>'test'
               
            ];
        } else {
            return [
                'success' => 'false',
                'message' => 'Email / Password Combination is wrong',
            ];
        }
    }

    public function actionChangepassword() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $model = Logins::findOne(Yii::$app->user->getId());
            $model->scenario = 'changepassword';
            if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->validate()) {
                $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->new_pass);
                $model->save();
                return [
                    'success' => 'true',
                    'message' => 'Password change successfully',
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'Incorrect password',
                ];
            }
        } else {
            return [
                'success' => true,
                'message' => 'Invalid request'
            ];
        }
    }
     public function actionForgotpassword() {
          $model = new Logins();
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->authenticate()) {
                
                return [
                    'success' => 'true',
                    'message' => 'Please verify your gmail account',
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'Incorrect email address',
                ];
            }
        } else {
            return [
                'success' => true,
                'message' => 'Invalid request'
            ];
        }
    }
    
    
}
