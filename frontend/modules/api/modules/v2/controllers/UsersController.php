<?php

namespace app\modules\api\modules\v2\controllers;

use common\models\Logins;
use common\models\Users;
use common\models\UsersCategories;
use common\models\UserTypes;
use common\models\UserTypesRights;
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
            'only' => ['listbyusertype', 'adduser', 'profile', 'editprofile', 'user_rolerights'],
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
            'only' => ['listbyusertype', 'adduser', 'profile', 'editprofile', 'user_rolerights'],
            'rules' => [
                    [
                    'actions' => ['listbyusertype', 'adduser', 'profile', 'editprofile', 'user_rolerights'],
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
            if ($model->save()) {
                return [
                    'success' => true,
                    'message' => 'Success',
                    'user_id' => $model->user_id
                ];
            } else {
                return [
                    'success' => 'false',
                    'message' => 'This username has been already exists'
                ];
            }
        } else {
            return [
                'success' => 'false',
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionUser_rolerights() {
        $post = Yii::$app->request->getBodyParams();

        if (!empty($post)) {
            $user = Users::find()
                    ->user($post['user_id'])
                    ->status()
                    ->active()
                    ->one();
            $user_type_rights = UserTypesRights::find()
                    ->where('user_type_id =' . $user['user_type_id'])
                    ->all();
            if ($user_type_rights) {
                foreach ($user_type_rights as $key => $value) {
                    $object[] = [
                        'role_id' => $value->right_id,
                        'role_name' => $value->right->rights,
                    ];
                }
                return [
                    'success' => true,
                    'message' => 'Success',
                    'role_rights' => $object,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'User has no rights'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionListbyusertype() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $user_id = Yii::$app->user->identity->user_id;
            $user = Users::find()->where(['user_id' => $user_id])->one();
            $categorylists = $user->categories;
            foreach ($categorylists as $categorylist) {
                $category_ids[] = $categorylist->category_id;
            }
            if (empty($category_ids))
                $category_ids[] = 0;
            if ($post['type_id'] == UserTypes::DE_USER_TYPE || $post['type_id'] == UserTypes::SE_USER_TYPE) {
                $dealer_categories = UsersCategories::find()
                        ->joinWith('user')
                        ->where(['category_id' => $category_ids])
                        ->andWhere(['=', 'el_users.user_type_id', $post['type_id']])
                        ->all();

                foreach ($dealer_categories as $dealer_category) {
                    $object[] = [
                        'user_id' => $dealer_category->user->user_id,
                        'user_type_id' => $dealer_category->user->user_type_id,
                        'name' => $dealer_category->user->name,
                        'address' => $dealer_category->user->address,
                        'mobile_no' => $dealer_category->user->mobile_no,
                        'email' => $dealer_category->user->email,
                    ];
                }
                if (!empty($dealer_categories)) {
                    return [
                        'success' => 'true',
                        'message' => 'Success',
                        'data' => $object
                    ];
                } else {
                    return [
                        'success' => true,
                        'message' => 'No records found',
                    ];
                }
            } elseif ($post['type_id'] == UserTypes::CU_USER_TYPE) {
                $users = Users::find()
                        ->userType($post['type_id'])
                        ->status()
                        ->active()
                        ->all();
            }
            if (!empty($users)) {
                foreach ($users as $user):
                    $object[] = [
                        'user_id' => $user->user_id,
                        'user_type' => $user->userType->type_name,
                        'name' => $user->name,
                        'address' => ($user->address == '') ? 'not set' : $user->address,
                        'mobile_no' => ($user->mobile_no == '') ? 'not set' : $user->mobile_no,
                        'email' => ($user->email == '') ? 'not set' : $user->email,
                    ];
                endforeach;
                return [
                    'success' => true,
                    'message' => 'Success',
                    'data' => $object
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
        $profile = Users::find()
//                    ->select('user_id,user_type_id,name,address,mobile_no,email')
                ->user(\Yii::$app->user->identity->user_id)
                ->status()
                ->active()
                ->one();

        $login = Logins::find()
                ->userid($profile->user_id)
                ->status()
                ->active()
                ->one();

        $object[] = [
            'user_id' => $profile->user_id,
            'user_type' => $profile->userType->type_name,
            'username' => (empty($login) || $login->username == '') ? 'not set' : $login->username,
            'fullname' => $profile->name,
            'address' => ($profile->address == '') ? 'not set' : $profile->address,
            'mobile_no' => ($profile->mobile_no == '') ? 'not set' : $profile->mobile_no,
            'email' => ($profile->email == '') ? 'not set' : $profile->email,
        ];
        if (!empty($profile)) {
            return [
                'success' => 'true',
                'message' => 'Success',
                'data' => $object
            ];
        } else {
            return [
                'success' => true,
                'message' => 'No records found',
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
//                    ->select('user_id,user_type_id,name,address,mobile_no')
                    ->user($post['user_id'])
                    ->status()
                    ->active()
                    ->one();

            $login = Logins::find()
                    ->userid($post['user_id'])
                    ->status()
                    ->active()
                    ->one();
            if (!empty($login) && !empty($post['email'])) {
                $login->email = $model->email;
                $login->save(false);
            }
            $object[] = [
                'user_id' => $profile->user_id,
                'user_type' => $profile->userType->type_name,
                'username' => (empty($login) || $login->username == '') ? 'not set' : $login->username,
                'fullname' => ($profile->name == '') ? 'not set' : $profile->name,
                'address' => ($profile->address == '') ? 'not set' : $profile->address,
                'mobile_no' => ($profile->mobile_no == '') ? 'not set' : $profile->mobile_no,
                'email' => ($profile->email == '') ? 'not set' : $profile->email,
            ];
            if (!empty($profile)) {
                return [
                    'success' => 'true',
                    'message' => 'Success',
                    'data' => $object
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

//    public function actionProductlist() {
//        $post = Yii::$app->request->getBodyParams();
//        $user = Users::find()->where(['user_id' => $post['user_id']])->one();
//        $categorylists = $user->categories;
//    }
}
