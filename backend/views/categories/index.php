<?php

use common\models\CategoriesSearch;
use common\models\SubCategories;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CategoriesSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Categories';
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
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <div class="pull-right">
                        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>   

            <div class="col-lg-12 col-md-12">&nbsp;</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
//                                    'category_id',
                                    'category_name',
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
                                    'created_at',
//                                    'updated_at',
                                    // 'created_by',
                                    // 'updated_by',
                                    // 'deleted_at',
                                    ['class' => 'yii\grid\ActionColumn',
                                        'template' => '{update}&nbsp;&nbsp;{delete}',
                                        'buttons' => [
                                            'delete' => function($url, $model) {
                                                $categorycount = SubCategories::find()->where(['category_id' => $model->category_id])->count();
                                                if (!$categorycount) {
                                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['category_id']], [
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


