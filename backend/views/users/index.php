<?php

use common\models\Orders;
use common\models\UsersSearch;
use common\models\UserTypes;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UsersSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="services-index">
    <div class="col-md-12">
        <div class="row">
            <div class="pull-right">
                <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">&nbsp;</div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                            'name',
                                [
                                'attribute' => 'user_type_id',
                                'value' => 'userType.type_name',
                                'filter' => Html::activeDropDownlist($searchModel, 'user_type_id', ArrayHelper::map(UserTypes::find()->where('status=:id', ['id' => 1])->all(), 'user_type_id', 'type_name'), ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
                            ],
                            'address:ntext',
                            'city',
                            'mobile_no',
                            'email',
                                [
                                'class' => 'backend\components\StatusColumn',
                                'attribute' => 'status',
                            ],
                                [
                                'attribute' => 'created_at',
                                'filter' => false,
                                'format' => ['date', 'php:Y-m-d H:i:s'],
                            ],
                                ['class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => '{update}&nbsp;&nbsp;{login}&nbsp;&nbsp;{delete}',
                                'buttons' => [
                                    'login' => function($url, $model) {
                                        $url = Url::toRoute('users/login?id=' . $model->user_id);
                                        return Html::a('<span title="Login" class="glyphicon glyphicon-lock"></span>', $url);
                                    },
                                    'delete' => function($url, $model) {
                                        $order = Orders::find()->where(['user_id' => $model->user_id])->one();
                                        if ($order) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->user_id], [
                                                        'class' => '',
                                                        'data' => [
                                                            'confirm' => 'This user has orders associated with it.Do you still wish to delete?',
                                                            'method' => 'post',
                                                        ],
                                            ]);
                                        } else {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->user_id], [
                                                        'class' => '',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete this user?',
                                                            'method' => 'post',
                                                        ],
                                            ]);
                                        }
                                    }
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>