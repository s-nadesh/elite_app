<?php

namespace backend\controllers;

use common\models\Categories;
use common\models\SubCategories;
use common\models\SubCategoriesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * SubCategoriesController implements the CRUD actions for SubCategories model.
 */
class SubCategoriesController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'getsubcategories'],
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
     * Lists all SubCategories models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubCategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubCategories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SubCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new SubCategories();
        $items = ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name');
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($_FILES['SubCategories']['name']['subcat_logo'])) {
                $model->subcat_logo = UploadedFile::getInstance($model, 'subcat_logo');
            } else {
                $model->subcat_logo = 'no-image.jpg';
            }
            $model->save();

            if ($model->subcat_logo != 'no-image.jpg') {
                $this->uploadLogo($model, 'subcat_logo');
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'items' => $items,
            ]);
        }
    }

    public function uploadLogo($model, $attr) {
        $folder = Yii::$app->basePath . '/web/uploads/subcategory/' . $model->subcat_id;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, TRUE);
        } else {
            FileHelper::removeDirectory($folder);
            mkdir($folder, 0777, TRUE);
        }
        $model->subcat_logo->saveAs($folder . '/' . $model->subcat_logo->baseName . '.' . $model->subcat_logo->extension);
    }

    /**
     * Updates an existing SubCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $items = ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name');
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($_FILES['SubCategories']['name']['subcat_logo'])) {
                $model->subcat_logo = UploadedFile::getInstance($model, 'subcat_logo');
            } else {
                $model->subcat_logo = 'no-image.jpg';
            }

            $model->save();

            if ($model->subcat_logo != 'no-image.jpg') {
                $this->uploadLogo($model, 'subcat_logo');
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'items' => $items,
            ]);
        }
    }

    /**
     * Deletes an existing SubCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SubCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SubCategories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //DepDrop widget
    // the getSubCatList function will query the database based on the
    // cat_id and return an array like below:
    // [
    //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
    //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
    // ]
    public function actionGetsubcategories() {
        $result = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $subcat = SubCategories::getSubcategories($cat_id, false);
                foreach ($subcat as $key => $value) {
                    $result[] = [
                        'id' => $value->subcat_id,
                        'name' => $value->subcat_name,
                    ];
                }
                echo Json::encode(['output' => $result, 'selected' => '']);
                exit;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
        exit;
    }

}
