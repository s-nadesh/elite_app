<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\Carts;
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
            'only' => ['productlist', 'addcart', 'editcart', 'deletecart'],
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
            'only' => ['productlist', 'addcart', 'editcart', 'deletecart'],
            'rules' => [
                [
                    'actions' => ['productlist', 'addcart', 'editcart', 'deletecart'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

//    public function actions() {
//        $actions = parent::actions();
//        unset($actions['productlist']);
//        return $actions;
//    }
    public function actionAddcart() {
        $model = new Carts();
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {

            $product_qnty = Products::find()
                    ->quantity_check($post['product_id'])
                    ->status()
                    ->active()
                    ->one();

            if ($post['qty'] <= $product_qnty->stock) {

                $cart_exist = Carts::find()
                        ->cartexist($post['user_id'], $post['ordered_by'], $post['product_id'])
                        ->status()
                        ->active()
                        ->one();

                if ($cart_exist) {
                    $cart_exist->load(Yii::$app->request->getBodyParams(), '');
                    $cart_exist->save();
                } else {
                    $model->load(Yii::$app->request->getBodyParams(), '');
                    $model->save();
                }


                $carts = Carts::find()
                        ->cart($post['user_id'], $post['ordered_by'])
                        ->status()
                        ->active()
                        ->all();

                foreach ($carts as $cart):
                    $total = $cart->qty * $cart->product->price_per_unit;
                    $object[] = [
                        'cart_id' => $cart->cart_id,
                        'ordered_by' => $cart->orderedBy->name,
                        'user_name' => $cart->user->name,
                        'category_id' => $cart->product->category->category_id,
                        'category_name' => $cart->product->category->category_name,
                        'subcat_id' => $cart->product->subcat->subcat_id,
                        'subcat_name' => $cart->product->subcat->subcat_name,
                        'product_id' => $cart->product->product_id,
                        'product_name' => $cart->product->product_name,
                        'total' => $total,
                        'qty' => $cart->qty,
                    ];

                endforeach;

                return [
                    'success' => true,
                    'message' => 'Success',
                    'cart' => $object
                ];
            }else {
                return [
                    'success' => true,
                    'message' => 'Current stock is ' . $product_qnty->stock,
                ];
            }
        } else {
            return [
                'success' => true,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionEditcart() {
        $post = Yii::$app->request->getBodyParams();
        $product_qnty = Products::find()
                ->quantity_check($post['product_id'])
                ->status()
                ->active()
                ->one();

        if ($post['qty'] <= $product_qnty->stock) {
            $model = Carts::findOne($post['cart_id']);
            if (!empty($model)) {
                $model->load(Yii::$app->request->getBodyParams(), '');
                $model->save();

                $cart = Carts::find()
                        ->editcart($post['cart_id'])
                        ->status()
                        ->active()
                        ->one();

                if (!empty($cart)) {
                    $total = $cart->qty * $cart->product->price_per_unit;
                    $object[] = [
                        'cart_id' => $cart->cart_id,
                        'ordered_by' => $cart->orderedBy->name,
                        'user_name' => $cart->user->name,
                        'category_id' => $cart->product->category->category_id,
                        'category_name' => $cart->product->category->category_name,
                        'subcat_id' => $cart->product->subcat->subcat_id,
                        'subcat_name' => $cart->product->subcat->subcat_name,
                        'product_id' => $cart->product->product_id,
                        'product_name' => $cart->product->product_name,
                        'total' => $total,
                        'qty' => $cart->qty,
                    ];


                    return [
                        'success' => 'true',
                        'message' => 'Success',
                        'data' => $object
                    ];
                }
            } else {
                return [
                    'success' => true,
                    'message' => 'Invalid request'
                ];
            }
        }else {
                return [
                    'success' => true,
                    'message' => 'Current stock is ' . $product_qnty->stock,
                ];
            }
    }

    public function actionDeletecart() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $model = Carts::findOne($post['cart_id']);
            if (!empty($model)) {
                $model->delete();
                return [
                    'success' => true,
                    'message' => 'Successfully deleted',
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

    public function actionProductlist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $products = Products::find()
                    ->select('product_id, product_name,price_per_unit,stock')
                    ->category($post['category_id'])
                    ->subcategory($post['subcat_id'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($products)) {
                return [
                    'success' => true,
                    'message' => 'Success',
                    'data' => $products
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
