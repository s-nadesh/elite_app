<?php

use common\models\Categories;
use common\models\Products;
use common\models\SubCategoriesSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SubCategoriesSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Sub Categories';
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
                        <?= Html::a('Create Sub Category', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'subcat_name',
                                        [
                                        'attribute' => 'category_id',
                                        'value' => 'category.category_name',
                                        'filter' => Html::activeDropDownlist($searchModel, 'category_id', ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name'), ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
                                    ],
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
                                    'created_at:datetime',
                                    // 'updated_at',
                                    // 'created_by',
                                    // 'updated_by',
                                    // 'deleted_at',
                                    ['class' => 'yii\grid\ActionColumn',
                                        'template' => '{update}&nbsp;&nbsp;{delete}',
                                        'buttons' => [
                                            'delete' => function($url, $model) {
                                                $subcategorycount = Products::find()->where(['subcat_id' => $model->subcat_id])->count();
                                                if (!$subcategorycount) {
                                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['subcat_id']], [
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
