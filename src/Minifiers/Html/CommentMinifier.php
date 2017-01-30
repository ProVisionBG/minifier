<?php

namespace ProVision\Minifier\Minifiers\Html;

use ProVision\Minifier\MinifierContext;
use ProVision\Minifier\Minifiers\MinifierInterface;

class CommentMinifier implements MinifierInterface
{
    /**
     * Replace remaining comments.
     * @param MinifierContext $context
     * @return MinifierContext
     */
    public function process(MinifierContext $context)
    {

        // Remove htmlcomments
        $additionaly = array(
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            //'/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\\\' | ")\/\/.*))/' => '', //@todo: да се помисли как да ги чисти по добре
        );
        return $context->setContents(preg_replace(array_keys($additionaly), array_values($additionaly), $context->getContents()));
    }

}
