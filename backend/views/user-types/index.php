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
                                    'class' => 'backend\components\StatusColumn',
                                    'attribute' => 'status',
                                ],
                                    [
                                    'attribute' => 'created_at',
                                    'filter' => false,
                                    'format' => ['date', 'php:Y-m-d H:i:s'],
                                ],
                                    [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Action',
                                    'template' => '{update}&nbsp;&nbsp;{delete}',
                                    'visibleButtons' => [
                                        'delete' => function($model, $key, $index) {
                                            $usercount = Users::find()->where(['user_type_id' => $model->user_type_id])->count();
                                            if ($usercount == 0 && $model->created_by > 0) {
                                                return true;
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