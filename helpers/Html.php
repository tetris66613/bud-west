<?php

namespace app\helpers;

class Html extends \yii\helpers\Html
{
    public static function wrap($content, $tags = [])
    {
        return self::_iterateTags($content, $tags, '_wrap');
    }

    public static function prepend($content, $tags)
    {
        return self::_iterateTags($content, $tags, '_prepend');
    }

    public static function append($content, $tags)
    {
        return self::_iterateTags($content, $tags, '_append');
    }

    protected static function _iterateTags($content, $tags, $funcName)
    {
        $prevTag = null;
        foreach ($tags as $value) {
            if (is_array($value)) {
                if (!$prevTag) continue;
                self::$funcName($content, $prevTag, $value);
                $prevTag = null;
            } else {
                if ($prevTag) {
                    self::$funcName($content, $prevTag);
                    $prevTag = null;
                }
                $prevTag = $value;
            }
        }

        if ($prevTag) {
            self::$funcName($content, $prevTag);
        }

        return $content;
    }

    protected static function _wrap(&$content, $tag, $options = [])
    {
        $content = self::tag($tag, $content, $options);
    }

    protected static function _append(&$content, $tag, $options = [])
    {
        $content .= self::endTag($tag);
    }

    protected static function _prepend(&$content, $tag, $options = [])
    {
        $content = self::beginTag($tag, $options) . $content;
    }
}
