<?php

namespace app\modules\api;

/**
 * api module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->modules = [
            'v1' => [
                'class' => 'app\modules\api\modules\v1\Module',
            ],
            'v2' => [
                'class' => 'app\modules\api\modules\v2\Module',
            ],
        ];
    }
}
