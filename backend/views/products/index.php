<?php

use common\models\Categories;
use common\models\ProductsSearch;
use common\models\SubCategories;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProductsSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Products';
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
                        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
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
                                    'product_name',
                                        [
                                        'attribute' => 'category_id',
                                        'value' => 'category.category_name',
                                        'filter' => Html::activeDropDownlist($searchModel, 'category_id', ArrayHelper::map(Categories::find()->where('status=:id', ['id' => 1])->all(), 'category_id', 'category_name'), ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
                                    ],
                                        [
                                        'attribute' => 'subcat_id',
                                        'value' => 'subcat.subcat_name',
                                        'filter' => Html::activeDropDownlist($searchModel, 'subcat_id', ArrayHelper::map(SubCategories::find()->where('status=:id', ['id' => 1])->all(), 'subcat_id', 'subcat_name'), ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
                                    ],
                                    'stock',
                                    'price_per_unit',
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
                                    // 'created_at',
                                    // 'updated_at',
                                    // 'created_by',
                                    // 'updated_by',
                                    // 'deleted_at',
                                    ['class' => 'yii\grid\ActionColumn',
                                        'template' => '{update}&nbsp;&nbsp;{delete}',
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