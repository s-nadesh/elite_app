<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Logins;
use common\models\ProductsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller {
    public $test = 'vicky';
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['login', 'forgotpassword'],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['logout', 'index', 'changepassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    /* n */
    public function actionIndex() {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->reorderList();
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    /* n */
    public function actionLogin() {
        $this->layout = "@app/views/layouts/login";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionForgotpassword() {
        $this->layout = "@app/views/layouts/login";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Logins();

        if ($model->load(Yii::$app->request->post()) && $model->authenticate()) {
            Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
            return $this->redirect('login');
        }
        return $this->render('forgotpassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    /* n */
    public function actionChangepassword() {
        $model = Logins::findOne(Yii::$app->user->getId());
        $model->scenario = 'changepassword';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->new_pass);
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Password changed successfully!!!');
            return $this->redirect(['/site/index']);
        }
        return $this->render('changepassword', [
                    'model' => $model,
        ]);
    }

    /* n */

    public function actionLogout() {
        Yii::$app->user->logout();
        $this->redirect(['/site/login']);
    }

}
