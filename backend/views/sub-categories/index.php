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
                                'template' => '{update}&nbsp;&nbsp;{delete}',
                                'visibleButtons' => [
                                    'delete' => function($model, $key, $index) {
                                        $subcategorycount = Products::find()->where(['subcat_id' => $model->subcat_id])->count();
                                        if ($subcategorycount == 0) {
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
