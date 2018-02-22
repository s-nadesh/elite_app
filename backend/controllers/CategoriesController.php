<?php

namespace backend\controllers;

use common\models\Categories;
use common\models\CategoriesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
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
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Categories();

        if ($model->load(Yii::$app->request->post())) {
            $post=Yii::$app->request->post();
            if (!empty($_FILES['Categories']['name']['cat_logo'])){
            $model->cat_logo = UploadedFile::getInstance($model, 'cat_logo');
            }else{
                   $model->cat_logo ='no-image.jpg';
            }
            $model->save();

            if ($model->cat_logo!='no-image.jpg') {
                $this->uploadLogo($model, 'cat_logo');
            }
            return $this->redirect(['index', 'id' => $model->category_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function uploadLogo($model, $attr) {
        $folder = Yii::$app->basePath . '/web/uploads/category/' . $model->category_id;

        if (!file_exists($folder)) {
            mkdir($folder, 0777, TRUE);
        } else {
            FileHelper::removeDirectory($folder);
            mkdir($folder, 0777, TRUE);
        }
        $model->cat_logo->saveAs($folder . '/' . $model->cat_logo->baseName . '.' . $model->cat_logo->extension);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
          if (!empty($_FILES['Categories']['name']['cat_logo'])){
            $model->cat_logo = UploadedFile::getInstance($model, 'cat_logo');
            }else{
                   $model->cat_logo ='no-image.jpg';
            }
            $model->save();
           if ($model->cat_logo!='no-image.jpg') {
                $this->uploadLogo($model, 'cat_logo');
            }
            return $this->redirect(['index', 'id' => $model->category_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
