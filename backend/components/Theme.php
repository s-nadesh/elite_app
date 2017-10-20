<?php

namespace backend\components;

/**
 *
 * @inheritdoc
 */
class Theme extends \yii\base\Theme
{
    /**
     * Theme folder name
     *
     * @var string
     */
    public $theme;

    public function init()
    {
        parent::init();

        if (!isset($this->theme)) {
            $this->theme = 'admin';
        }

        $this->basePath = '@app/web/themes/' . $this->theme;
        $this->baseUrl = '@web/themes/' . $this->theme;
        $this->pathMap = [
            '@backend/views' => '@app/web/themes/' . $this->theme,
        ];
    }
}