<?php

namespace app\modules\api\modules\v1\controllers;

use common\models\Carts;
use common\models\OrderBillings;
use common\models\OrderItems;
use common\models\Orders;
use common\models\OrderStatus;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class OrderController extends ActiveController {

    public $modelClass = 'common\models\Orders';

    public function behaviors() {
        $behaviors = parent::behaviors();
        //Authenticator - It is used to login the user by using header (Authorization Bearer Token).
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['confirmorder', 'vieworder', 'orderlist'],
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
            'only' => ['confirmorder', 'vieworder', 'orderlist'],
            'rules' => [
                [
                    'actions' => ['confirmorder', 'vieworder', 'orderlist'],
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



    public function actionConfirmorder() {
        $model = new Orders();
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $model->load(Yii::$app->request->getBodyParams(), '');
            $model->total_amount = $post['items_total_amount'];
            $model->save(false);
            $cartlist = Carts::find()
                    ->cart($post['user_id'], $post['ordered_by'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($cartlist)) {
                foreach ($cartlist as $cart):
                    $order_item = new OrderItems();
                    $order_item->order_id = $model->order_id;
                    $total = $cart->qty * $cart->product->price_per_unit;

                    $order_item->category_id = $cart->product->category->category_id;
                    $order_item->subcat_id = $cart->product->subcat->subcat_id;
                    $order_item->product_id = $cart->product_id;
                    $order_item->category_name = $cart->product->category->category_name;
                    $order_item->subcat_name = $cart->product->subcat->subcat_name;
                    $order_item->product_name = $cart->product->product_name;
                    $order_item->price = $cart->product->price_per_unit;
                    $order_item->quantity = $cart->qty;
                    $order_item->total = $total;

                    $order_item->save();

                    $cart->delete();
                endforeach;
                return [
                    'success' => true,
                    'message' => 'Order has been placed successfully',
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

    public function actionOrderlist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $order = Orders::find()
                    ->orders($post['order_id'])
                    ->status()
                    ->active()
                    ->one();
            if (!empty($order)) {
                $paid_amount = OrderBillings::paidAmount($order->order_id);
                $pending_amount = OrderBillings::pendingAmount($order->items_total_amount, $paid_amount);

                $object[] = [
                    'order_id' => $order->order_id,
                    'invoice_no' => $order->invoice_no,
                    'order_placed_on' => $order->invoice_date,
                    'status' => $order->orderStatus->status_name,
//                    'user_name' => $order->user->name,
//                    'product_name' => $order->orderItems->product->product_name,
//                    'status_id' => $order->order_status_id,
                ];
                $object1[] = [
                    'user_name' => $order->user->name,
                    'user_email' => $order->user->email,
                    'user_address' => $order->user->address,
                    'user_phone' => $order->user->mobile_no,
                ];
                foreach ($order->orderItems as $getorder):
                    $object2[] = [
                        'product_id' => $getorder->product_id,
                        'product_category' => $getorder->category_name,
                        'product_subcategory' => $getorder->subcat_name,
                        'product_name' => $getorder->product_name,
                        'price_per_unit' => $getorder->price,
                        'quantity' => $getorder->quantity,
                    ];
                endforeach;

                $object3[] = [
                    'total_amount' => $order->items_total_amount,
                    'pending_amount' => $pending_amount,
                    'received_amt' => ($paid_amount == null) ? 0 : $paid_amount,
                ];

                foreach ($order->orderBillings as $getorderpayment):
                    $pending_amount = OrderBillings::pendingAmount($order->items_total_amount, $getorderpayment->paid_amount);
                    $object4[] = [
                        'date' => date('d/M/Y', $getorderpayment->created_at),
                        'received_amount' => $getorderpayment->paid_amount,
                        'pending_amount' => $pending_amount,
                        'total_amount' => $order->items_total_amount,
                    ];
                endforeach;

                foreach ($order->orderTrack as $info):
                    $responseArray = json_decode($info->value, true);
                    if ($info->order_status_id == OrderStatus::OR_DISPATCHED) {
                        $object5[] = [
                            'track_id' => $responseArray['dispatch_track_id'],
                            'dispatch_date' => $info->created_at,
                            'courier_company_name' => $responseArray['dispatch_courier_comapny'],
                            'comments' => $responseArray['dispatch_comment'],
                        ];
                    } elseif ($info->order_status_id == OrderStatus::OR_DELEVERED) {
                        $object6[] = [
                            'deliver_name' => $responseArray['deliver_to'],
                            'deliver_date' => $info->created_at,
                            'deliver_mobile' => $responseArray['deliver_phone'],
                            'deliver_addr' => $responseArray['deliver_address'],
                        ];
                    } elseif ($info->order_status_id == OrderStatus::OR_CANCELED) {
                        $object7[] = [
                            'cancel_comments' => $responseArray['cancel_comment'],
                        ];
                    }
                endforeach;
                return [
                    'success' => true,
                    'message' => 'success',
                    'data' => $object,
                    'user_data' => $object1,
                    'product_details' => $object2,
                    'amount_details' => $object3,
                    'payment_log_details' => $object4,
                    'dispatch_details' => $object5,
                    'delivery_details' => $object6,
                    'cancel_details' => $object7,
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

    public function actionVieworder() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $orderlist = Orders::find()
                    ->orderlist($post['ordered_by'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($orderlist)) {
                foreach ($orderlist as $order):
                    $paid_amount = OrderBillings::paidAmount($order->order_id);
                    $pending_amount = OrderBillings::pendingAmount($order->items_total_amount, $paid_amount);

                    $object[] = [
                        'order_id' => $order->order_id,
                        'invoice_no' => $order->invoice_no,
                        'order_placed_on' => $order->invoice_date,
                        'user_name' => $order->user->name,
//                    'product_name' => $order->orderItems->product->product_name,
                        'total_amount' => $order->items_total_amount,
                        'pending_amount' => $pending_amount,
                        'status' => $order->orderStatus->status_name,
                        'status_id' => $order->order_status_id,
                    ];
                endforeach;
                return [
                    'success' => true,
                    'message' => 'success',
                    'data' => $object,
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
