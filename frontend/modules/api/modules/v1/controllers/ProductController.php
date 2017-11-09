<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\Products;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class ProductController extends ActiveController {

    public $modelClass = 'common\models\Products';

    public function behaviors() {
        $behaviors = parent::behaviors();
        //Authenticator - It is used to login the user by using header (Authorization Bearer Token).
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['productlist'],
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
            'only' => ['productlist'],
            'rules' => [
                [
                    'actions' => ['productlist'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();
        unset($actions['productlist']);
        return $actions;
    }
    
    public function actionProductlist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
        $products = Products::find()
                ->select('product_id, product_name')
                ->category($post['category_id'])
                ->subcategory($post['subcat_id'])
                ->status()
                ->active()
                ->all();
        if (!empty($products)) {
            return [
                'success' => true,
                'message' => 'Success',
                'products' => $products
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
