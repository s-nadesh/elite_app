<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\LoginForm;
use common\models\Logins;
use common\models\User;
use common\models\UserTypes;
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
        $user = new User();
        $admin_typeid = UserTypes::AD_USER_TYPE;
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $user_login = Logins::find()
                    ->user($post['username'])
                    ->status()
                    ->active()
                    ->one();

            if ($user_login && $user_login->user->user_type_id != $admin_typeid) {
                $password = $user_login->password_hash;
                if (Yii::$app->security->validatePassword($post['password'], $password)) {
                    return [
                        'success' => 'true',
                        'message' => 'Login successful',
                        'user_id' => $user_login->user->user_id,
                        'access_token' => $user_login->auth_key,
                    ];
                } else {
                    return [
                        'success' => 'false',
                        'message' => 'Email / Password Combination is wrong',
                    ];
                }
            } else {
                return [
                    'success' => 'false',
                    'message' => 'Invalid request'
                ];
            }
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
                    'success' => 'false',
                    'message' => 'Incorrect password',
                ];
            }
        } else {
            return [
                'success' => 'false',
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
                    'message' => 'New password has been sent to your gmail account.Kindly check it.',
                ];
            } else {
                return [
                    'success' => 'false',
                    'message' => 'Incorrect email address',
                ];
            }
        } else {
            return [
                'success' => 'false',
                'message' => 'Invalid request'
            ];
        }
    }

}
