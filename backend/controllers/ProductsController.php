<?php

namespace backend\controllers;

use common\models\Categories;
use common\models\Products;
use common\models\ProductsSearch;
use common\models\StockLog;
use common\models\StockLogSearch;
use common\models\SubCategories;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'getsubcatlist', 'getproduct', 'getproducts', 'stocklog'],
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
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionGetsubcatlist() {
        if (Yii::$app->request->isAjax) {

            $cat_id = $_POST['id'];
            if ($cat_id != 0) {
                $subcatlists = SubCategories::find()->andWhere([
                            'category_id' => $cat_id,
                            'status' => 1,
                        ])->all();
                $lists = ArrayHelper::map($subcatlists, "subcat_id", function($model, $defaultValue) {
                            return $model->subcat_name;
                        });
                if (!empty($lists)) {
                    $slist = "<option value=''>--Select Subcategory--</option>";
                    foreach ($lists as $key => $list) {

                        $slist .= "<option value='" . $key . "'>" . $list . "</option>";
                    }
                } else {
                    $slist = "<option value=''>No Records</option>";
                }

                echo $slist;
                exit;
            }
        }
    }

    public function actionCreate() {
        $model = new Products();
        $categories = Categories::getCategories();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'categories' => $categories,
            ]);
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $categories = ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name');
        $sub_categories = ArrayHelper::map(SubCategories::find()->where('status=:id', ['id' => 1])->all(), 'subcat_id', 'subcat_name');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'categories' => $categories,
                        'sub_categories' => $sub_categories,
            ]);
        }
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionStocklog($id) {
        $model = $this->findModel($id);
        $stock = StockLog::find()->where(['product_id' => $model->product_id])->one();
        $stock = new StockLog();
        if ($stock->load(Yii::$app->request->post())) {
            $stock->adjust_from = $model->stock;
            if ($stock->adjust_quantity != NULL) {
                $quantity = $stock->adjust_quantity + $model->stock;
            } else {
                $quantity = $model->stock;
            }
            $model->stock = $quantity;
            $model->save();
            $stock->product_id = $model->product_id;
            $stock->adjust_to = $model->stock;
            $stock->save();
            return $this->redirect(['stocklog?id=' . $model->product_id]);
        }
        $searchModel = new StockLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('stocklog', [
                    'model' => $this->findModel($id),
                    'stock' => $stock,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetproduct($id) {
        $product = $this->findModel($id);
        echo Json::encode($product);
        return;
    }

    //DepDrop widget
    /**
     * the getProdList function will query the database based on the
     * cat_id and sub_cat_id and return an array like below:
     *  [
     *      'out'=>[
     *          ['id'=>'<prod-id-1>', 'name'=>'<prod-name1>'],
     *          ['id'=>'<prod_id_2>', 'name'=>'<prod-name2>']
     *       ],
     *       'selected'=>'<prod-id-1>'
     *  ]
     */
    public function actionGetproducts() {
        $result = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $cat_id = empty($ids[0]) ? null : $ids[0];
            $subcat_id = empty($ids[1]) ? null : $ids[1];
            if ($cat_id != null && $subcat_id != null) {
                $data = Products::getProducts($cat_id, $subcat_id, false);
                foreach ($data as $key => $value) {
                    $result[] = [
                        'id' => $value->product_id,
                        'name' => $value->product_name,
                    ];
                }
                echo Json::encode(['output' => $result, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

}
