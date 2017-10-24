<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle {

    public $css = [
//        '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css',
        '//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css',
        'css/morris/morris.css',
        'css/jvectormap/jquery-jvectormap-1.2.2.css',
        'css/datepicker/datepicker3.css',
        'css/daterangepicker/daterangepicker-bs3.css',
        'css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'css/AdminLTE.css',
//        'css/AdminLTE.min.css',
        'css/custom.css',
    ];
    public $js = [
//        '//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js',
        '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js',
        '//code.jquery.com/ui/1.11.1/jquery-ui.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
//        'js/plugins/morris/morris.min.js',
        'js/plugins/sparkline/jquery.sparkline.min.js',
        'js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'js/plugins/jqueryKnob/jquery.knob.js',
        'js/plugins/daterangepicker/daterangepicker.js',
        'js/plugins/datepicker/bootstrap-datepicker.js',
        'js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        'js/plugins/iCheck/icheck.min.js',
        'js/AdminLTE/app.js',
//        'js/AdminLTE/dashboard.js',
        'js/AdminLTE/demo.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init() {
        parent::init();
        if (isset(Yii::$app->view->theme->basePath)) {
            $this->sourcePath = Yii::$app->view->theme->basePath;
        }
    }

}
