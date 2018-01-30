<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Glyphicon extends Widget
{
    public $icon;
    public $tag = 'span';
    public $content = '';
    public $tagOptions = [];

    public function run()
    {
        if (!isset($this->tagOptions['class'])) {
            $this->tagOptions['class'] = '';
        }

        if (isset($this->icon)) {
            $this->tagOptions['class'] = 'glyphicon glyphicon-' . $this->icon . ' ' . $this->tagOptions['class'];
        }

        return Html::tag($this->tag, $this->content, $this->tagOptions);
    }

    public static function icon($icon, $config = [])
    {
        $config['icon'] = $icon;

        return self::widget($config);
    }
}
