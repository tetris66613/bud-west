<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;

class Ajax extends Widget
{
    public $action;
    public $replaceFunc = 'html';
    public $replacement = '$(this).closest(\'form\')';

    public $params;
    public $options = [];


    public function run()
    {
        $url = $this->prepareUrl($this->action);
        $replacement = ($this->replacement[0] == '$') ? $this->replacement : '$(\'' . $this->replacement . '\')';
        $func = $this->replaceFunc;

        return "
            var obj = $replacement;
            $.ajax({
                type: 'POST',
                url: '$url',
                data: $(this).closest('form').serialize(),
                success: function(response) {
                    obj.$func(response);
                },
                error: function() {
                    console.log('Problem with updating model');
                }
            })
        ";
    }

    public static function post($action, $replacement = '', $config = [])
    {
        $config['action'] = $action;
        if (!empty($replacement) && is_string($replacement)) {
            $config['replacement'] = $replacement;
        }

        return self::widget($config);
    }


    protected function prepareUrl($url)
    {
        $action = '';
        if (is_array($url)) {
            $action = Yii::$app->urlManager->createUrl($url);
        } else {
            $actoin = $url;
        }

        return $action;
    }
}
