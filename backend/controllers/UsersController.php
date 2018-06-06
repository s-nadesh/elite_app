<?php

namespace backend\controllers;

use common\models\Categories;
use common\models\Logins;
use common\models\Users;
use common\models\UsersCategories;
use common\models\UsersSearch;
use common\models\UserTypes;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'login', 'gettype_showinapp'],
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGettype_showinapp() {
        if (Yii::$app->request->isAjax) {
            $type_id = $_POST['id'];
            $type = UserTypes::find()->where('user_type_id=:id', ['id' => $type_id])->one();
            echo $type->email_app_login;
            exit;
//            print_r($type->visible_site);exit;
        }
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Users();
        $items = ArrayHelper::map(UserTypes::find()->where('status=:id', ['id' => 1])->all(), 'user_type_id', 'type_name');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $categorylists = Yii::$app->request->post('Users')['categorylist'];
            $users = Users::findOne($model->user_id);
            if ($categorylists) {
                foreach ($categorylists as $categorylist) {
                    $categories[] = Categories::findOne($categorylist);
                }
                $extraColumns = ['status' => 1]; // extra columns to be saved to the many to many table
                $unlink = true; // unlink tags not in the list
                $delete = true; // delete unlinked tags
                $users->linkAll('categories', $categories, $extraColumns, $unlink, $delete);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'items' => $items,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $items = ArrayHelper::map(UserTypes::find()->where('status=:id', ['id' => 1])->all(), 'user_type_id', 'type_name');

        $category_list = $model->categories;
        foreach ($category_list as $value) {
            $get[] = $value->category_id;
        }
        if (empty($get))
            $get[] = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $categorylists = Yii::$app->request->post('Users')['categorylist'];
            $users = Users::findOne($model->user_id);
            if ($categorylists) {
                foreach ($categorylists as $categorylist) {
                    $categories[] = Categories::findOne($categorylist);
                }
                $extraColumns = ['status' => 1]; // extra columns to be saved to the many to many table
                $unlink = true; // unlink tags not in the list
                $delete = true; // delete unlinked tags
                $users->linkAll('categories', $categories, $extraColumns, $unlink, $delete);
            }
            $login = Logins::find()->where(['user_id' => $model->user_id])->one();
            if (!empty($login)) {
                $login->email = $model->email;
                $login->save(false);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'items' => $items,
                        'get' => $get,
            ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLogin($id) {
        $model = $this->findModel($id);
        $login = Logins::find()->where(['user_id' => $model->user_id])->one();
        if (!empty($login)) {
            $login->scenario = 'update';
            if ($login->load(Yii::$app->request->post()) && $login->validate()) {
                $pass = $_POST['Logins']['password_hash'];
                $login->setPassword($pass);
                $login->save(false);
                return $this->redirect(['index']);
            }
        } else {
            $login = new Logins();
            $login->email = $model->email;
            $login->user_id = $model->user_id;
            if ($login->load(Yii::$app->request->post()) && $login->validate()) {
                $login->setPassword($login->password_hash);
                $login->generateAuthKey();
                $login->save(false);
                return $this->redirect(['index']);
            }
        }
        return $this->render('login', [
                    'login' => $login,
        ]);
    }

}
