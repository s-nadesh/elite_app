<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

class LoginThemeAsset extends AssetBundle {

    public $css = [
//        '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css',
        'css/AdminLTE.css',
    ];
    public $js = [
//        '//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js',
        '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];

    public function init() {
        parent::init();
        if (isset(Yii::$app->view->theme->basePath)) {
            $this->sourcePath = Yii::$app->view->theme->basePath;
        }
    }

}
