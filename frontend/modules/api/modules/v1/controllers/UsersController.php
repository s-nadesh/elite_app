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
            'only' => ['listbyusertype'],
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
            'only' => ['listbyusertype'],
            'rules' => [
                    [
                    'actions' => ['listbyusertype'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
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

}
