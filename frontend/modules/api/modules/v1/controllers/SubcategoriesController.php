<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\SubCategories;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class SubcategoriesController extends ActiveController {

    public $modelClass = 'common\models\SubCategories';

    public function behaviors() {
        $behaviors = parent::behaviors();
        //Authenticator - It is used to login the user by using header (Authorization Bearer Token).
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['subcategorylist'],
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
            'only' => ['subcategorylist'],
            'rules' => [
                [
                    'actions' => ['subcategorylist'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['subcategorylist']);
        return $actions;
    }
    
    public function actionSubcategorylist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
        $subcategories = SubCategories::find()
                ->select('subcat_id, subcat_name')
                ->category($post['category_id'])
                ->status()
                ->active()
                ->all();
        if (!empty($subcategories)) {
            return [
                'success' => true,
                'message' => 'Success',
                'data' => $subcategories
            ];
        } else {
            return [
                'success' => true,
                'message' => 'No records found',
            ];
        }
        }else {
            return [
                'success' => true,
                'message' => 'Invalid request'
            ];
        }
    }

}
