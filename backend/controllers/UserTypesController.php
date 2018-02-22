<?php

namespace backend\controllers;

use common\models\UserTypesRights;
use common\models\UserTypes;
use common\models\UserTypesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserTypesController implements the CRUD actions for UserTypes model.
 */
class UserTypesController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'rights'],
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
     * Lists all UserTypes models.
     * @return mixed
     */
    /* checked */
    public function actionIndex() {
        $searchModel = new UserTypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserTypes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new UserTypes();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            if (!empty($model->rightslist)) {
                foreach ($model->rightslist as $value) {
                    UserTypesRights::createRights($value, $model->user_type_id);
                }
            }
            Yii::$app->getSession()->setFlash('success', 'Type added successfully!!!');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $right_list = UserTypesRights::find()
                ->where(['user_type_id' => $model->user_type_id])
                ->all();
        foreach ($right_list as $value) {
            $get[] = $value->right_id;
        }
        if (empty($get))
            $get[] = 0;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $rights_in_post = $model->rightslist;
            if (!empty($rights_in_post)) {
                $result = array_diff($get, $rights_in_post);
            } else {
                $result = $get;
            }
            foreach ($result as $value) {
                UserTypesRights::getResult($value, $model->user_type_id);
            }
            if (!empty($model->rightslist)) {
                $update_rights = array_diff($rights_in_post, $get);
                foreach ($update_rights as $value) {
                    UserTypesRights::createRights($value, $model->user_type_id);
                }
            }

            return $this->redirect(['index', 'id' => $model->user_type_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'get' => $get
            ]);
        }
    }

    /**
     * Deletes an existing UserTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = UserTypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
