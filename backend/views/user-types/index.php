<?php

use common\models\Users;
use common\models\UserTypesSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UserTypesSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'User Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<aside class="right-side">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </section>
    <section class="content">
        <div class="services-index">
            <div class="col-md-12">
                <div class="row">
                    <div class="pull-right">
                        <?= Html::a('Create User Type', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'type_name',
                                    'type_code',
                                        [
                                        'attribute' => 'status',
                                        'filter' => Html::activeDropDownlist($searchModel, 'status', ["0" => 'In active', '1' => 'active'], ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
                                        'value' => function($model) {
                                            if ($model->status == 1) {
                                                return '<span class="label success">Active</span>';
                                            } else {
                                                return '<span class="label failure">InActive</span>';
                                            }
                                        },
                                        'format' => 'raw'],
                                        [
                                        'attribute' => 'created_at',
                                        'value' => function ($model, $key, $index, $grid) {
                                            return date('Y-m-d H:i:s', $model->created_at);
                                        },
                                    ],
//                        'updated_at:datetime',
//                        [
//                            'attribute' => 'updated_at',
//                            'value' => function ($model, $key, $index, $grid) {
//                                return date('Y-m-d H:i:s', $model->updated_at);
//                            },
//                        ],
                                    // 'created_by',
                                    // 'updated_by',
                                    // 'deleted_at',
                                    ['class' => 'yii\grid\ActionColumn',
                                        'template' => '{update}&nbsp;&nbsp;{delete}',
                                        'buttons' => [
                                            'delete' => function($url, $model) {
                                                $usercount = Users::find()->where(['user_type_id' => $model->user_type_id])->count();
                                                if (!$usercount) {
                                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['user_type_id']], [
                                                                'title' => Yii::t('app', 'Delete'), 'data-confirm' => Yii::t('app', 'Are you sure you want to delete this usertype?'), 'data-method' => 'post']);
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
    </section>
</aside>
