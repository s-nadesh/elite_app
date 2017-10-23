<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<aside class="right-side">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <!--        <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Tables</a></li>
                    <li class="active">Data tables</li>
                </ol>-->
        <?php $this->params['breadcrumbs'][] = $this->title; ?>
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
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-body">

                            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                            <p>

                            </p>
                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
//                        'user_type_id',
                                    'type_name',
                                    'type_code',
//                        'visible_site',
//                        'reorder_notify',
                                    'status',
//                        'created_at:datetime',
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
                                        'template' => '{update} {delete}',
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
