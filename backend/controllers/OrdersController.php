<?php

namespace backend\controllers;

use common\models\Carts;
use common\models\Categories;
use common\models\OrderBillings;
use common\models\OrderBillingsSearch;
use common\models\OrderItems;
use common\models\Orders;
use common\models\OrdersSearch;
use common\models\OrderTrack;
use common\models\OrderTrackSearch;
use common\models\Products;
use common\models\SubCategories;
use common\models\Users;
use common\models\UserTypes;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
                        'actions' => ['index', 'create', 'status', 'billing', 'update', 'view', 'delete', 'getsubcategorylist', 'getproductlist', 'placeorder'],
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
    /*n*/
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
    public function actionView($id) {
        $searchModel = new OrderBillingsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
       
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                            ]);
    }

    public function actionGetsubcategorylist() {
        if (Yii::$app->request->isAjax) {
            $cat_id = $_POST['id'];
            print_r($cat_id);
            exit;
            if ($cat_id != 0) {
                $subcatlists = SubCategories::find()->andWhere([
                            'category_id' => $cat_id,
                            'status' => 1,
                        ])->all();
                $subcategory_list = ArrayHelper::map($subcatlists, "subcat_id", function($model, $defaultValue) {
                            return $model->subcat_name;
                        });


                if (!empty($subcategory_list)) {
                    $slist = "<option value=''>--Select Subcategory--</option>";
                    foreach ($subcategory_list as $key => $sinfo) {

                        $slist .= "<option value='" . $key . "'>" . $sinfo . "</option>";
                    }
                } else {
                    $slist = "<option value=''>No Records</option>";
                }

                echo $slist;
                exit;
            }
        }
    }

    public function actionGetproductlist() {
        if (Yii::$app->request->isAjax) {
            $subcat_id = $_POST['id'];
            if ($subcat_id != 0) {
                $productlists = Products::find()->andWhere([
                            'subcat_id' => $subcat_id,
                            'status' => 1,
                        ])->all();
                $getproduct_list = ArrayHelper::map($productlists, "product_id", function($model, $defaultValue) {
                            return $model->product_name;
                        });

                if (!empty($getproduct_list)) {
                    $slist = "<option value=''>--Select Product--</option>";
                    foreach ($getproduct_list as $key => $sinfo) {

                        $slist .= "<option value='" . $key . "'>" . $sinfo . "</option>";
                    }
                } else {
                    $slist = "<option value=''>No Records</option>";
                }

                echo $slist;
                exit;
            }
        }
    }

    public function actionStatus($id) {
        $model = $this->findModel($id);
        $model->scenario = 'createadmin';
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
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

    public function actionBilling($id) {
        $model = $this->findModel($id);
        $orderbilling_model = new OrderBillings();
        $paid_amount = OrderBillings::paidAmount($id);
        $pending_amount = OrderBillings::pendingAmount($model->total_amount, $paid_amount);
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $orderbilling_model->load(Yii::$app->request->post());
            if ($model->save()) {
                OrderBillings::insertOrderBilling($model, $orderbilling_model);
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

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Orders();
        $odritem_model = new OrderItems();
        $orderbilling_model = new OrderBillings();
        $orderby = ArrayHelper::map(Users::find()->where('user_type_id=:id', ['id' => UserTypes::SE_USER_TYPE])->all(), 'user_id', 'name');
        $users = ArrayHelper::map(Users::find()->where('user_type_id=:id or user_type_id=:id1', ['id' => UserTypes::CU_USER_TYPE, 'id1' => UserTypes::DE_USER_TYPE])->all(), 'user_id', 'name');
        $categories = ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name');
        $sub_categories = ArrayHelper::map(SubCategories::find()->where('status=:id', ['id' => 1])->all(), 'subcat_id', 'subcat_name');
        $products = ArrayHelper::map(Products::find()->where('status=:id', ['id' => 1])->all(), 'product_id', 'product_name');


        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $odritem_model->load(Yii::$app->request->post());
            $orderbilling_model->load(Yii::$app->request->post());

            if ($model->validate() && $odritem_model->validate() && $orderbilling_model->validate()) {
                $model->save();
                $odritem_model->save();
                $orderbilling_model->save();
            }
            return $this->redirect(['view', 'id' => $model->order_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'odritem_model' => $odritem_model,
                        'orderby' => $orderby,
                        'users' => $users,
                        'categories' => $categories,
                        'sub_categories' => $sub_categories,
                        'products' => $products,
                        'orderbilling_model' => $orderbilling_model,
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
        $orderby = ArrayHelper::map(Users::find()->where('user_type_id=:id', ['id' => UserTypes::SE_USER_TYPE])->all(), 'user_id', 'name');
        $users = ArrayHelper::map(Users::find()->where('user_type_id=:id or user_type_id=:id1', ['id' => UserTypes::CU_USER_TYPE, 'id1' => UserTypes::DE_USER_TYPE])->all(), 'user_id', 'name');
        $paid_amount = OrderBillings::paidAmount($id);
        $pending_amount = OrderBillings::pendingAmount($model->total_amount, $paid_amount);
        if (Yii::$app->request->post()) {

            if ($orderbilling_model->load(Yii::$app->request->post())) {
                $orderbilling_model->order_id = $model->order_id;
                if ($orderbilling_model->validate()) {
                    $orderbilling_model->save();
                    Yii::$app->getSession()->setFlash('success', 'Paid Amount added successfully');
                    return $this->redirect(['orders/index']);
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
                    'orderby' => $orderby,
                    'users' => $users,
                    'orderbilling_model' => $orderbilling_model,
                    'paid_amount' => $paid_amount,
                    'pending_amount' => $pending_amount,
                    'tmodel' => $tmodel,
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
