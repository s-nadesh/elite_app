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
                            'category_name',
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
                                        $categorycount = SubCategories::find()->where(['category_id' => $model->category_id])->count();
                                        if ($categorycount == 0) {
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


