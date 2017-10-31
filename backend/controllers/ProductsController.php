<?php

namespace backend\controllers;

use common\models\Categories;
use common\models\Products;
use common\models\ProductsSearch;
use common\models\SubCategories;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'getsubcatlist'],
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
        $categories = ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name');
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

}
