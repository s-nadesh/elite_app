<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\Categories;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class CategoriesController extends ActiveController {

    public $modelClass = 'common\models\Categories';

    public function behaviors() {
        $behaviors = parent::behaviors();
        //Authenticator - It is used to login the user by using header (Authorization Bearer Token).
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

    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }
    
    public function actionIndex() {
        $categories = Categories::find()
                ->select('category_id, category_name')
                ->status()
                ->active()
                ->all();
        if (!empty($categories)) {
            return [
                'success' => true,
                'message' => 'Success',
                'data' => $categories
            ];
        } else {
            return [
                'success' => true,
                'message' => 'No records found',
            ];
        }
    }

}
