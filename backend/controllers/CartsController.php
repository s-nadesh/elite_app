<?php

namespace backend\controllers;

use common\models\Carts;
use common\models\CartSearch;
use common\models\Categories;
use common\models\Users;
use common\models\UserTypes;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CartsController implements the CRUD actions for Carts model.
 */
class CartsController extends Controller {

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
     * Lists all Carts models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new Carts();
        $searchModel = new CartSearch();
        $session = Yii::$app->session;

        //After form submit
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->sessionid = $session->getId();
            $cart_exists = Carts::cartExists($model);
            if ($cart_exists) {
                $new_qty = $model->qty;
                $old_qty = $cart_exists->qty;
                $model = $cart_exists;
                $model->qty = $old_qty + $new_qty;
            }

            //Set session
            $session['carts'] = [
                'sessionid' => $model->sessionid,
                'user_id' => $model->user_id,
                'ordered_by' => $model->ordered_by,
            ];

            $model->save(false);
            $model = new Carts(); //reset model
        }

        $queryParams = [];

        //index.php - filterModel hide, so below code not need.
//        if(!empty(Yii::$app->request->queryParams)){
//            $queryParams = Yii::$app->request->queryParams;
//        }  
        //Set dealers and sales executive.
        if (isset($session['carts'])) {
            $model->sessionid = $session['carts']['sessionid'];
            $model->user_id = $session['carts']['user_id'];
            $model->ordered_by = $session['carts']['ordered_by'];
            $queryParams['CartSearch'] = $session['carts'];
        } else {
            //Shows only current session products in cart.
            $queryParams['CartSearch'] = ['sessionid' => $session->getId()];
        }

        $dataProvider = $searchModel->search($queryParams);
        
        //Form input datas
        $user_types = UserTypes::CU_USER_TYPE . ',' . UserTypes::DE_USER_TYPE;
        $users = Users::getUsersbytype($user_types);
        $sales_exe = UserTypes::SE_USER_TYPE . ',' . UserTypes::BE_USER_TYPE;
        $sales = Users::getUsersbytype($sales_exe);
        $categories = Categories::getCategories();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'users' => $users,
                    'sales' => $sales,
                    'categories' => $categories,
        ]);
    }

    /**
     * Displays a single Carts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Carts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Carts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cart_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Carts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cart_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Carts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Carts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Carts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Carts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
