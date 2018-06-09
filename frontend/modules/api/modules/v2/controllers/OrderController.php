<?php

namespace app\modules\api\modules\v2\controllers;

use common\models\Carts;
use common\models\OrderBillings;
use common\models\OrderItems;
use common\models\Orders;
use common\models\OrderStatus;
use common\models\OrderTrack;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\web\UploadedFile;

class OrderController extends ActiveController {

    public $modelClass = 'common\models\Orders';

    public function behaviors() {
        $behaviors = parent::behaviors();
        //Authenticator - It is used to login the user by using header (Authorization Bearer Token).
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['confirmorder', 'vieworderlist', 'edit_cancelcomment', 'orderview', 'statuslist', 'changestatus', 'makepayment', 'filterstatuslist', 'viewitemlist', 'viewamount_itemlist'],
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
            'only' => ['confirmorder', 'vieworderlist', 'edit_cancelcomment', 'orderview', 'statuslist', 'changestatus', 'makepayment', 'viewamount_itemlist', 'filterstatuslist', 'viewitemlist'],
            'rules' => [
                    [
                    'actions' => ['confirmorder', 'vieworderlist', 'edit_cancelcomment', 'orderview', 'statuslist', 'changestatus', 'viewamount_itemlist', 'makepayment', 'filterstatuslist', 'viewitemlist'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function actionConfirmorder() {
        $model = new Orders();
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $cartlist = Carts::find()
                    ->cart($post['user_id'], $post['ordered_by'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($cartlist)) {
                $model->load(Yii::$app->request->getBodyParams(), '');
                $model->change_status = true;
                $model->items_total_amount = 0.00;
                $model->total_amount = 0.00;
                $model->save(false);
                foreach ($cartlist as $cart):
                    $order_item = new OrderItems();
                    $order_item->order_id = $model->order_id;
//                    $total = $cart->qty * $cart->product->price_per_unit;

                    $order_item->category_id = $cart->product->category->category_id;
                    $order_item->subcat_id = empty($cart->product->subcat->subcat_id) ? '' : $cart->product->subcat->subcat_id;
                    $order_item->product_id = $cart->product_id;
                    $order_item->category_name = $cart->product->category->category_name;
                    $order_item->subcat_name = empty($cart->product->subcat->subcat_name) ? 'not set' : $cart->product->subcat->subcat_name;
                    $order_item->product_name = $cart->product->product_name;
                    $order_item->quantity = $cart->qty;
                    $order_item->save();

                    $cart->delete();
                endforeach;
                return [
                    'success' => true,
                    'message' => 'Order has been placed successfully',
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

    public function actionOrderview() {
        $post = Yii::$app->request->getBodyParams();
        $summ = 0;
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
                    'order_status_id' => $order->order_status_id,
                    'status' => $order->orderStatus->status_name,
                ];
                $object1[] = [
                    'user_name' => $order->user->name,
                    'user_email' => ( $order->user->email == "" ) ? 'not set' : $order->user->email,
                    'user_address' => $order->user->address,
                    'user_phone' => $order->user->mobile_no,
                ];
                foreach ($order->orderItems as $getorder):
                    $object2[] = [
                        'product_id' => $getorder->product_id,
                        'product_category' => $getorder->category->category_name,
                        'product_subcategory' => empty($getorder->subcat->subcat_name) ? 'not set' : $getorder->subcat->subcat_name,
                        'product_name' => $getorder->product->product_name,
                        'product_logo' => $getorder->product->product_logo,
                        'price_per_unit' => $getorder->total,
                        'quantity' => $getorder->quantity,
                    ];
                endforeach;

                $object3[] = [
                    'total_amount' => $order->items_total_amount,
                    'pending_amount' => $pending_amount,
                    'received_amt' => ($paid_amount == null) ? 0 : $paid_amount,
                ];

                foreach ($order->orderBillings as $getorderpayment):
                    $summ += $getorderpayment->paid_amount;
                    $pending_amount = OrderBillings::pendingAmount($order->items_total_amount, $summ);
                    $object4[] = [
                        'date' => date('d/M/Y', $getorderpayment->created_at),
                        'received_amount' => $getorderpayment->paid_amount,
                        'pending_amount' => $pending_amount,
                        'total_amount' => $order->items_total_amount,
                        'signature' => $getorderpayment->signature,
                    ];
                endforeach;

                foreach ($order->orderTrack as $info):
                    if ($info->value) {
                        $responseArray = json_decode($info->value, true);
                        $tracklist[] = [
                            'track_status' => $info->orderStatus->status_name,
                            'date' => date('d/M/Y', $info->created_at),
                            'order_track_id' => $info->order_track_id,
                            'value' => [
                                $responseArray
                            ]
                        ];
                    } else {
                        $tracklist[] = [
                            'track_status' => $info->orderStatus->status_name,
                            'date' => date('d/M/Y', $info->created_at)
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
                    'order_track' => $tracklist,
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

    public function actionFilterstatuslist() {
        $order_status = OrderStatus::find()
                ->select('order_status_id,status_name')
                ->status()
                ->active()
                ->all();
        if (!empty($order_status)) {

            return [
                'success' => true,
                'message' => 'success',
                'data' => $order_status,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No records found',
            ];
        }
    }

    public function actionStatuslist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $order_status = OrderStatus::find()
                    ->select('order_status_id,status_name')
                    ->orderstatuslist($post['order_status_id'])
                    ->status()
                    ->active()
                    ->all();
            if (!empty($order_status)) {

                return [
                    'success' => true,
                    'message' => 'success',
                    'data' => $order_status,
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

    public function actionChangestatus() {
        $post = Yii::$app->request->getBodyParams();
        $model = Orders::findOne($post['order_id']);

        if (!empty($model)) {

            if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->validate()) {

                if (!empty($post['invoice_no']))
                    $model->invoice_no = $post['invoice_no'];
                if ($post['order_status_id'] == OrderStatus::OR_INPROGRESS) {
                    foreach ($post['amount_arrayList'] as $key => $product_arrayvalue):
                        foreach ($product_arrayvalue as $key => $getvalue) {
                            $item = OrderItems::find()
                                    ->where('order_id =' . $post['order_id'] . ' and product_id =' . $key)
                                    ->one();
                            if (!empty($item)) {
                                $quantity = $item->quantity;
                                $total_amount = $getvalue;
                                $price_per_unit = $total_amount / $quantity;
                                $item->price = $price_per_unit;
                                $item->total = $getvalue;
                                $item->save();
                            }
                        }
                    endforeach;
                }
                if ($post['order_status_id'] == OrderStatus::OR_CANCELED) {
                    $order_track = OrderTrack::find()
                            ->cancel_order_track($post['order_id'], $post['order_status_id'])
                            ->status()
                            ->active()
                            ->one();
                    if ($order_track) {
                        $data = [];
                        if ($order_track->order_status_id == OrderStatus::OR_CANCELED) {
                            $data['cancel_comment'] = $post['cancel_comment'];
                        }
                        if (!empty($data))
                            $order_track->value = json_encode($data, true);
                        $order_track->save(false);
                    }
                }
                if (empty($order_track))
                    $model->change_status = true;
                if ($post['order_status_id'] == OrderStatus::OR_INPROGRESS)
                    $model->items_total_amount = $post['total_amount'];
                $model->save();
                return [
                    'success' => true,
                    'message' => 'success',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invoice Number has already been taken.'
                ];
            }
            return [
                'success' => true,
                'message' => 'success',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionMakepayment() {
        $post = Yii::$app->request->getBodyParams();
        $orderbilling_model = new OrderBillings();
        if (!empty($post)) {

            $orderbilling_model->load(Yii::$app->request->getBodyParams(), '');
            $orderbilling_model->order_id = $post['order_id'];
            $orderbilling_model->paid_amount = $post['paid_amount'];
            $signature = UploadedFile::getInstancesByName("signature");
            foreach ($signature as $file) {
                $hidespace_image = str_replace(' ', '-', $file->name);
                $randno = rand(11111, 99999);
                $path = Yii::$app->basePath . '/web/uploads/signature/' . $randno . $hidespace_image;
                $file->saveAs($path);
                $orderbilling_model->signature = $randno . $hidespace_image;
            }
            $orderbilling_model->save();
            return [
                'success' => true,
                'message' => 'success',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Invalid request'
            ];
        }
    }

    public function actionEdit_cancelcomment() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $ordertrack = OrderTrack::find()
                    ->ordertrack($post['order_track_id'])
                    ->status()
                    ->active()
                    ->one();

            if (!empty($ordertrack)) {
                $responseArray = json_decode($ordertrack['value'], true);
                $data = [];
                $data['cancel_comment'] = $post['cancel_comment'];
                $ordertrack->value = json_encode($data, true);
                $ordertrack->save();
                return [
                    'success' => true,
                    'message' => 'success',
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

    public function actionViewitemlist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $orderlist = OrderItems::find()
                    ->orderlist($post['order_id'])
                    ->status()
                    ->active()
                    ->all();

            if (!empty($orderlist)) {
                foreach ($orderlist as $order):
                    $object[] = [
                        'category_name' => $order->category_name,
                        'subcat_name' => $order->subcat_name,
                        'product_id' => $order->product_id,
                        'product_name' => $order->product_name,
                        'product_logo' => $order->product->product_logo,
                        'quantity' => $order->quantity,
                    ];
                endforeach;
                return [
                    'success' => true,
                    'message' => 'success',
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

//    public function actionViewamount_itemlist() {
//        $post = Yii::$app->request->getBodyParams();
//        if (!empty($post)) {
//            foreach ($post['amount_arrayList'] as $key => $product_arrayvalue):
//                foreach ($product_arrayvalue as $key => $getvalue) {
//                    $item = OrderItems::find()
//                            ->where('order_id =' . $post['order_id'] . ' and product_id =' . $key)
//                            ->one();
//                    if (!empty($item)) {
//                        $item->total = $getvalue;
//                        $item->save();
//                    }
//                }
//            endforeach;
//            $order = Orders::find()
//                    ->orders($post['order_id'])
//                    ->status()
//                    ->active()
//                    ->one();
//            if (!empty($order)) {
//                $order->order_status_id = $post['order_status_id'];
//                $order->items_total_amount = $post['total_amount'];
//                $order->total_amount = $post['total_amount'];
//                $order->save();
////                foreach ($orderlist as $order):
////                    $object[] = [
////                        'category_name' => $order->category_name,
////                        'subcat_name' => $order->subcat_name,
////                        'product_id' => $order->product_id,
////                        'product_name' => $order->product_name,
////                        'product_name' => $order->product->product_logo,
////                        'quantity' => $order->quantity,
////                    ];
////                endforeach;
//                return [
//                    'success' => true,
//                    'message' => 'success',
////                    'data' => $object,
//                ];
//            } else {
//                return [
//                    'success' => false,
//                    'message' => 'No records found',
//                ];
//            }
//        } else {
//            return [
//                'success' => false,
//                'message' => 'Invalid request'
//            ];
//        }
//    }

    public function actionVieworderlist() {
        $post = Yii::$app->request->getBodyParams();
        if (!empty($post)) {
            $getorders = Orders::find();
            if (!empty($post['started_at']) && !empty($post['end_at'])) {
                $getorders->getorders($post['started_at'], $post['end_at']);
            }
            if (!empty($post['order_status_id']) && $post['order_status_id'] != 0) {
                $getorders->orderstatus($post['order_status_id']);
            }
            if (!empty($post['search_by'])) {
                $getorders->searchby($post['search_by'], $getorders);
            }
            $orderlist = $getorders->orderlist($post['ordered_by'])
                    ->orderBy(['updated_at' => SORT_DESC])
                    ->status()
                    ->active()
                    ->all();

            if (!empty($orderlist)) {
                foreach ($orderlist as $order):
                    $paid_amount = OrderBillings::paidAmount($order->order_id);
//                    print_r($order->items_total_amount);exit;
                    $pending_amount = OrderBillings::pendingAmount($order->items_total_amount, $paid_amount);

                    $object[] = [
                        'order_id' => $order->order_id,
                        'invoice_no' => $order->invoice_no,
                        'order_placed_on' => $order->invoice_date,
                        'user_name' => $order->user->name,
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

}
