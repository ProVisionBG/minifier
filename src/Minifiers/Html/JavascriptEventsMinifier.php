<?php

namespace ProVision\Minifier\Minifiers\Html;

use ProVision\Minifier\Constants;
use ProVision\Minifier\Minifier;
use ProVision\Minifier\MinifierContext;
use ProVision\Minifier\Minifiers\MinifierInterface;
use ProVision\Minifier\MinifyContext;
use ProVision\Minifier\Options;

class JavascriptEventsMinifier implements MinifierInterface
{
    /**
     * Minify javascript prefixes on html event attributes.
     * @param MinifierContext $context
     * @return MinifierContext
     */
    public function process(MinifierContext $context)
    {
        $contents = preg_replace_callback(
            '/' .
            'on' . Minifier::ATTRIBUTE_NAME_REGEX . '   # Match an on{attribute}
                \s*=\s*             # Match equals sign with optional whitespaces around it
                ["\']?              # Match an optional quote
                \s*javascript:      # Match the text "javascript:" which should be removed
            /xis',
            function ($match) {
                return str_replace('javascript:', '', $match[0]);
            }, $context->getContents());

        return $context->setContents($contents);
    }
}
