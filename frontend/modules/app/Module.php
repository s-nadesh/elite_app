<?php

namespace app\modules\app;

/**
 * app module definition class
 */
class Module extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\app\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\app\modules\v1\Module',
            ],
        ];
    }

}
