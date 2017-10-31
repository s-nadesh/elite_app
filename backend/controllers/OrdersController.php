<?php

namespace backend\controllers;

use common\models\Categories;
use common\models\OrderBillings;
use common\models\OrderItems;
use common\models\Orders;
use common\models\OrdersSearch;
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
class OrdersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => [''],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['index', 'create', 'update', 'view', 'delete','getsubcategorylist','getproductlist'],
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
    public function actionIndex()
    {
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
     public function actionGetsubcategorylist() {
        if (Yii::$app->request->isAjax) {
            $cat_id = $_POST['id'];
            print_r($cat_id);exit;
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
    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();
        $odritem_model = new OrderItems();
        $orderbilling_model = new OrderBillings();
        $orderby = ArrayHelper::map(Users::find()->where('user_type_id=:id', ['id' => UserTypes::SE_USER_TYPE])->all(), 'user_id', 'name');
        $users = ArrayHelper::map(Users::find()->where('user_type_id=:id or user_type_id=:id1', ['id' => UserTypes::CU_USER_TYPE,'id1'=>UserTypes::DE_USER_TYPE])->all(), 'user_id', 'name');
        $categories = ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name');
        $sub_categories = ArrayHelper::map(SubCategories::find()->where('status=:id', ['id' => 1])->all(), 'subcat_id', 'subcat_name');
        $products = ArrayHelper::map(Products::find()->where('status=:id', ['id' => 1])->all(), 'product_id', 'product_name');

                                
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $odritem_model->load(Yii::$app->request->post());
             $orderbilling_model->load(Yii::$app->request->post());
            
             if ($model->validate() && $odritem_model->validate() && $orderbilling_model->validate() ) {

                $model->save();
                 $odritem_model->save();
                  $orderbilling_model->save();
             }
            return $this->redirect(['view', 'id' => $model->order_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'odritem_model'=>$odritem_model,
                'orderby'=>$orderby,
                'users'=>$users,
                'categories' => $categories,
                'sub_categories' => $sub_categories,
                'products'=>$products,
                'orderbilling_model'=>$orderbilling_model,
            ]);
        }
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
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
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
