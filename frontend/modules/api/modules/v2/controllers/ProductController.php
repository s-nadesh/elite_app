<?php

namespace app\modules\api\modules\v2\controllers;

use common\models\Carts;
use common\models\Products;
use common\models\Users;
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
            'only' => ['productlist', 'addcart', 'editcart', 'deletecart', 'stockcheck', 'placeorder', 'cartlist', 'emptycart'],
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
            'only' => ['productlist', 'addcart', 'editcart', 'deletecart', 'stockcheck', 'placeorder', 'cartlist', 'emptycart'],
            'rules' => [
                    [
                    'actions' => ['productlist', 'addcart', 'editcart', 'deletecart', 'stockcheck', 'placeorder', 'cartlist', 'emptycart'],
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

    public function actionStockcheck() {
        $post = Yii::$app->request->getBodyParams();
        $product_qnty = Products::find()
                ->quantity_check($post['product_id'])
                ->status()
                ->active()
                ->one();
        if (!empty($product_qnty)) {
            if ($post['qty'] <= $product_qnty->stock) {
                return [
                    'success' => true,
                    'message' => 'Current stock is ' . $product_qnty->stock,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No stock available,Current stock is ' . $product_qnty->stock
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionAddcart() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {

            //old codings--------------
//            $cart_exist = Carts::find()
//                    ->cartexist($post['user_id'], $post['ordered_by'], $post['product_id'])
//                    ->status()
//                    ->active()
//                    ->one();
//            if ($cart_exist) {
//                $total_quan = $post['qty'] + $cart_exist->qty;
//                $cart_exist->load(Yii::$app->request->getBodyParams(), '');
//                $cart_exist->qty = $total_quan;
//                $cart_exist->save();
//              
//            } else {
//                 $model->load(Yii::$app->request->getBodyParams(), '');
//                $model->save();
//            }


            foreach ($post['product_arrayList'] as $key => $product_arrayvalue):

                foreach ($product_arrayvalue as $key => $getvalue) {

                    $model = new Carts();

                    $cart_exist = Carts::find()
                            ->cartexist($post['user_id'], $post['ordered_by'], $key)
                            ->status()
                            ->active()
                            ->one();
                    $sum_qty_column = Carts::find()
                            ->where('user_id =' . $post['user_id'] . ' and ordered_by =' . $post['ordered_by'] . ' and product_id =' . $key)
                            ->sum('qty');
                    $sum = $getvalue + $sum_qty_column;
                    $prodcut_stock = Products::findOne($key);
                    if ($sum > $prodcut_stock->stock) {
                        return [
                            'success' => false,
                            'message' => 'Current stock is only ' . $prodcut_stock->stock . ' for ' . $prodcut_stock->product_name
                        ];
                    } else {

                        if ($cart_exist) {
                            $total_quan = $getvalue + $cart_exist->qty;
                            $cart_exist->load(Yii::$app->request->getBodyParams(), '');
                            $cart_exist->qty = $total_quan;
                            $cart_exist->save();
                        } else {
                            $model->load(Yii::$app->request->getBodyParams(), '');
                            $model->product_id = $key;
                            $model->qty = $getvalue;
                            $model->save();
                        }
                    }
//                    $model->load(Yii::$app->request->getBodyParams(), '');
//                    $model->product_id = $key;
//                    $model->qty = $getvalue;
//                    $model->save();
                }
            endforeach;

            $carts = Carts::find()
                    ->cart($post['user_id'], $post['ordered_by'])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->status()
                    ->active()
                    ->all();


            foreach ($carts as $cart):
//                $total = $cart->qty * $cart->product->price_per_unit;
                $object[] = [
                    'cart_id' => $cart->cart_id,
                    'ordered_by' => $cart->orderedBy->name,
                    'user_name' => $cart->user->name,
                    'category_id' => $cart->product->category->category_id,
                    'category_name' => $cart->product->category->category_name,
                    'subcat_id' => (!empty($cart->product->subcat->subcat_id)) ? $cart->product->subcat->subcat_id : 'not set',
                    'subcat_name' => (!empty($cart->product->subcat->subcat_name)) ? $cart->product->subcat->subcat_name : 'not set',
                    'product_id' => $cart->product->product_id,
                    'product_name' => $cart->product->product_name,
//                    'total' => $total,
                    'qty' => $cart->qty,
                ];

            endforeach;
            return [
                'success' => true,
                'message' => 'Success',
                'cart' => $object
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionEditcart() {
        $post = Yii::$app->request->getBodyParams();

        $model = Carts::findOne($post['cart_id']);
        if (!empty($model)) {
//            if (!empty($post['qty'])) {
//                $total_quan = $post['qty'] + $model->qty;
//                $model->qty = $total_quan;
//            }
            $model->load(Yii::$app->request->getBodyParams(), '');
            $model->save();

            $cart = Carts::find()
                    ->editcart($post['cart_id'])
                    ->status()
                    ->active()
                    ->one();

            if (!empty($cart)) {
//                $total = $cart->qty * $cart->product->price_per_unit;
                $object[] = [
                    'cart_id' => $cart->cart_id,
                    'ordered_by' => $cart->orderedBy->name,
                    'user_name' => $cart->user->name,
                    'category_id' => $cart->product->category->category_id,
                    'category_name' => $cart->product->category->category_name,
                    'subcat_id' => (!empty($cart->product->subcat->subcat_id)) ? $cart->product->subcat->subcat_id : 'not set',
                    'subcat_name' => (!empty($cart->product->subcat->subcat_name)) ? $cart->product->subcat->subcat_name : 'not set',
                    'product_id' => $cart->product->product_id,
                    'product_name' => $cart->product->product_name,
//                    'total' => $total,
                    'qty' => $cart->qty,
                ];


                return [
                    'success' => true,
                    'message' => 'Success',
                    'data' => $object
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Data doesnot exist'
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
                    'success' => false,
                    'message' => 'No records found',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionCartlist() {

        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {

            $cats = Products::find()
                    ->select('el_products.category_id')
                    ->joinWith(['carts'])
                    ->orderBy(['el_carts.created_at' => SORT_DESC])
                    ->where('user_id =' . $post['user_id'] . ' and ordered_by =' . $post['ordered_by'] . ' and el_products.product_id = el_carts.product_id')
                    ->groupBy(['category_id'])
                    ->all();

            foreach ($cats as $key => $cat):
                $cartlist = Carts::find()
                        ->joinWith(['product'])
                        ->orderBy(['el_carts.created_at' => SORT_DESC])
                        ->where('user_id =' . $post['user_id'] . ' and ordered_by =' . $post['ordered_by'] . ' and el_products.category_id =' . $cat['category_id'])
                        ->all();
                foreach ($cartlist as $key => $products):
                    $object2[] = [
                        'cart_id' => $products['cart_id'],
                        'subcat_id' => empty($products->product->subcat_id) ? 'not set' : $products->product->subcat_id,
                        'subcat_name' => empty($products->product->subcat->subcat_name) ? 'not set' : $products->product->subcat->subcat_name,
                        'product_id' => $products['product_id'],
                        'product_name' => $products->product->product_name,
                        'product_logo' => $products->product->product_logo,
                        'stock' => $products->product->stock,
                        'qty' => $products->qty,
                    ];
                endforeach;
                $object1[] = [
                    'category_id' => $cat['category_id'],
                    'category_name' => $cat['category']['category_name'],
                    'product_array' => $object2,
                ];
                unset($object2);
            endforeach;
            $object[] = [
                'ordered_by' => $products->orderedBy->name,
                'user_name' => $products->user->name,
                'category_array' => $object1,
            ];
            if (!empty($cartlist)) {
                return [
                    'success' => true,
                    'message' => 'Success',
                    'data' => $object,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No records found',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionProductlist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $user = Users::find()->where(['user_id' => $post['user_id']])->one();
            $categorylists = $user->categories;
            if (!empty($categorylists)) {
                foreach ($categorylists as $categorylist) {
                    $productlists = Products::find()->where(['category_id' => $categorylist->category_id])->all();
                }

                foreach ($productlists as $productlist) {
                    $object[] = [
                        'product_id' => $productlist->product_id,
                        'product_name' => $productlist->product_name,
                        'stock' => $productlist->stock,
                        'price_per_unit' => $productlist->price_per_unit,
                        'product_logo' => $productlist->product_logo,
                    ];
                }
            }
            if (!empty($productlists)) {
                return [
                    'success' => 'true',
                    'message' => 'Success',
                    'selected_category_id' => $categorylist->category_id,
                    'selected_category_name' => $categorylist->category_name,
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

    public function actionPlaceorder() {
        $post = Yii::$app->request->getBodyParams();
//        $summ = 0;
        if (!empty($post)) {
            $carts = Carts::find()
                    ->cart($post['user_id'], $post['ordered_by'])
                    ->orderBy(['created_at' => SORT_DESC])
                    ->status()
                    ->active()
                    ->all();

            if (!empty($carts)) {

                foreach ($carts as $cart):

//                    $total = $cart->qty * $cart->product->price_per_unit;
//                    $summ += $total;
                    $object[] = [
                        'cart_id' => $cart->cart_id,
                        'ordered_by' => $cart->orderedBy->name,
                        'user_name' => $cart->user->name,
                        'user_email' => $cart->user->email,
                        'user_address' => $cart->user->address,
                        'user_phone' => $cart->user->mobile_no,
                        'category_id' => $cart->product->category->category_id,
                        'category_name' => $cart->product->category->category_name,
                        'subcat_id' => (!empty($cart->product->subcat->subcat_id)) ? $cart->product->subcat->subcat_id : 'not set',
                        'subcat_name' => (!empty($cart->product->subcat->subcat_name)) ? $cart->product->subcat->subcat_name : 'not set',
                        'product_id' => $cart->product->product_id,
                        'product_name' => $cart->product->product_name,
                        'product_logo' => $cart->product->product_logo,
//                        'product_rate' => $total,
                        'qty' => $cart->qty,
                    ];

                endforeach;

                return [
                    'success' => 'true',
                    'message' => 'Success',
                    'data' => $object,
//                    'total' => $summ
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No records found',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionEmptycart() {
        $user_id = Yii::$app->user->identity->user_id;
        $checkuser = Carts::find()->where(['ordered_by' => $user_id])->all();
        if (!empty($checkuser)) {
            $cart = Carts::deleteAll(['ordered_by' => $user_id]);
            return [
                'success' => true,
                'message' => 'Your Cart is Empty now',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No records found',
            ];
        }
    }

}
