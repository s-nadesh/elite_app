<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class UsersController extends ActiveController {

    public $modelClass = 'common\models\Users';

    public function behaviors() {
        $behaviors = parent::behaviors();
        //Authenticator - It is used to login the user by using header (Authorization Bearer Token).
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['listbyusertype','adduser', 'profile', 'editprofile'],
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
            'only' => ['listbyusertype','adduser', 'profile', 'editprofile'],
            'rules' => [
                    [
                    'actions' => ['listbyusertype','adduser', 'profile', 'editprofile'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionAdduser() {
         $model = new Users();
         $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
           $model->load(Yii::$app->request->getBodyParams(), '');
                $model->save();
            
            return [
            'success' => true,
            'message' => 'Success',
            'user_id' => $model->user_id
        ];
            
        }else {
            return [
                'success' => true,
                'message' => 'Invalid request'
            ];
        }
    }
    public function actionListbyusertype() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $users = Users::find()
                    ->select('user_id, user_type_id, name, address, mobile_no, email')
                    ->userType($post['type_id'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($users)) {
                return [
                    'success' => true,
                    'message' => 'Success',
                    'data' => $users
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'No records found',
                ];
            }
        } else {
            return [
                'success' => true,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionProfile() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $profile = Users::find()
                    ->select('user_id,user_type_id,name,address,mobile_no,email')
                    ->user($post['user_id'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($profile)) {
                return [
                    'success' => 'true',
                    'message' => 'Success',
                    'data' => $profile
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'No records found',
                ];
            }
        } else {
            return [
                'success' => true,
                'message' => 'Invalid request'
            ];
        }
    }
    public function actionEditprofile() {
        $post = Yii::$app->request->getBodyParams();
        $model = Users::findOne($post['user_id']);
        if (!empty($post)) {
            $model->load(Yii::$app->request->getBodyParams(), '');
            $model->save();
            $profile = Users::find()
                    ->select('user_id,user_type_id,name,address,mobile_no')
                    ->user($post['user_id'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($profile)) {
                return [
                    'success' => 'true',
                    'message' => 'Success',
                    'data' => $profile
                ];
            } else {
                return [
                    'success' => true,
                    'message' => 'No records found',
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
