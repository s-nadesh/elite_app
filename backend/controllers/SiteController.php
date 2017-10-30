<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Logins;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                        [
                        'actions' => ['logout', 'index', 'changepassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
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
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
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

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionChangepassword() {
        $model = Logins::findOne(Yii::$app->user->getId());
        $model->scenario = 'changepassword';
        if ($model->load(Yii::$app->request->post())) {
            $pass = $model->password_hash;
            if ($model->validate()) {
                $model->new_pass = $_POST['Logins']['new_pass'];
                $hash = Yii::$app->getSecurity()->generatePasswordHash($model->new_pass);
                $model->password_hash = $hash;
            }
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Changed the password successfully!!!');
            $this->redirect(array('/site/index'));
        }
        return $this->render('changepassword', [
                    'model' => $model,
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        $this->redirect(array('/site/login'));
    }

}
