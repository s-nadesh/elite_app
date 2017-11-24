<?php

namespace backend\controllers;

use common\models\Carts;
use common\models\OrderBillings;
use common\models\OrderBillingsSearch;
use common\models\OrderItems;
use common\models\Orders;
use common\models\OrdersSearch;
use common\models\OrderStatus;
use common\models\OrderTrack;
use common\models\Products;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => [''],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['index', 'create', 'status', 'billing', 'update', 'view', 'delete', 'getsubcategorylist', 'getproductlist', 'placeorder', 'edittrack'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    /* n */
    public function actionIndex() {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    /* n */
    public function actionView($id) {
        $model = $this->findModel($id);
        $searchModel = new OrderBillingsSearch();
        $order_billing_search = ['OrderBillingsSearch' => ['order_id' => $id]];
        $dataProvider = $searchModel->search($order_billing_search);
        $paid_amount = $model->orderBillingsSum;
        $pending_amount = OrderBillings::pendingAmount($model->total_amount, $paid_amount);

        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'pending_amount' => $pending_amount,
        ]);
    }

    /* n */

    public function actionStatus($id) {
        $model = $this->findModel($id);
        $model1 = OrderTrack::find()->where(['order_id' => $id])->one();
//        $model->scenario = 'createadmin';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->change_status = true;
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Status changed successfully');
                return $this->redirect(['index']);
            }
        } else {
            return $this->renderAjax('status', [
                        'model' => $model,
            ]);
        }
    }

    /* n */

    public function actionBilling($id) {
        $model = $this->findModel($id);
        $orderbilling_model = new OrderBillings();
        $paid_amount = $model->orderBillingsSum;
        $pending_amount = OrderBillings::pendingAmount($model->total_amount, $paid_amount);
        if (Yii::$app->request->post()) {
            $orderbilling_model->load(Yii::$app->request->post());
            $orderbilling_model->order_id = $model->order_id;
            if ($orderbilling_model->validate()) {
                $orderbilling_model->save();
                Yii::$app->getSession()->setFlash('success', 'Payment updated successfully');
                return $this->redirect(['index']);
            }
        } else {
            return $this->renderAjax('billing', [
                        'model' => $model,
                        'orderbilling_model' => $orderbilling_model,
                        'paid_amount' => $paid_amount,
                        'pending_amount' => $pending_amount,
            ]);
        }
    }

    //Cancelled Order Status  - Edit Track
    public function actionEdittrack($id) {
        $order_track = OrderTrack::findOne($id);
        $model = $order_track->order;
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $data = [];
            if ($order_track->order_status_id == OrderStatus::OR_CANCELED) {
                $data['cancel_comment'] = $post['Orders']['cancel_comment'];
            }
            if (!empty($data))
                $order_track->value = json_encode($data, true);
            $order_track->save(false);
            return $this->redirect(['orders/view?id=' . $model->order_id]);
        } else {
            $responseArray = json_decode($order_track->value, true);
            if ($order_track->order_status_id == OrderStatus::OR_CANCELED) {
                $model->cancel_comment = $responseArray['cancel_comment'];
            }
            return $this->renderAjax('edittrack', [
                        'model' => $model,
                        'order_track' => $order_track,
            ]);
        }
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $orderbilling_model = new OrderBillings();
        $tmodel = new OrderTrack();

        $model->scenario = 'createadmin';
        $paid_amount = $model->orderBillingsSum;
        $pending_amount = OrderBillings::pendingAmount($model->total_amount, $paid_amount);

        if (Yii::$app->request->post()) {
            if ($orderbilling_model->load(Yii::$app->request->post())) {
                $orderbilling_model->order_id = $model->order_id;
                if ($orderbilling_model->validate()) {
                    $orderbilling_model->save();
                    Yii::$app->getSession()->setFlash('success', 'Paid Amount added successfully');
                    return $this->redirect(['orders/update?id=' . $model->order_id]);
                }
            } elseif ($model->load(Yii::$app->request->post())) {
                $model->change_status = true;
                if ($model->validate()) {
                    $model->save();
                    Yii::$app->getSession()->setFlash('success', 'updated successfully');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'orderbilling_model' => $orderbilling_model,
                    'paid_amount' => $paid_amount,
                    'pending_amount' => $pending_amount,
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function prepareCartitems($carts) {
        $cart_items = [];

        foreach ($carts as $cart) {
            $cart_items[] = [
                'category_id' => $cart->product->category_id,
                'subcat_id' => $cart->product->subcat_id,
                'product_id' => $cart->product_id,
                'category_name' => $cart->product->category->category_name,
                'subcat_name' => $cart->product->subcat->subcat_name,
                'product_name' => $cart->product->product_name,
                'quantity' => $cart->qty,
                'price' => $cart->product->price_per_unit,
                'total' => ($cart->qty * $cart->product->price_per_unit),
            ];
        }

        return $cart_items;
    }

    public function actionPlaceorder() {
        $session = Yii::$app->session;
        if (isset($session['carts'])) {
            $cart_detail = $session['carts'];

            $carts = Carts::find()
                    ->andWhere($cart_detail)
                    ->all();

            if (!empty($carts)) {
                $cart_items = $this->prepareCartitems($carts);

                //Order Insert
                $order = new Orders();
                $order->change_status = true;
                $order->user_id = $cart_detail['user_id'];
                $order->ordered_by = $cart_detail['ordered_by'];
                $order->items_total_amount = Orders::calcItemsTotal($cart_items);
                if ($order->save(false)) {
                    foreach ($cart_items as $cart_item) {
                        $order_item = new OrderItems();
                        $order_item->order_id = $order->order_id;
                        $order_item->load($cart_item, '');
                        $order_item->save(false);
                    }
                    Carts::clearCart(); // Pending
                    Yii::$app->session->setFlash('success', "Order placed successfully");
                    return $this->redirect(['orders/index']);
                }
            } else {
                return $this->redirect(['carts/index']);
            }
        } else {
            return $this->redirect(['carts/index']);
        }
    }

}
