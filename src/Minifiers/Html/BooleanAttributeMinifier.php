<?php

namespace ProVision\Minifier\Minifiers\Html;

use ProVision\Minifier\MinifierContext;
use ProVision\Minifier\Minifiers\MinifierInterface;

class BooleanAttributeMinifier implements MinifierInterface
{
    /**
     * Execute the minification rule.
     *
     */
    public function process(MinifierContext $context)
    {

        $booleanAttributes = implode('|', EmptyAttributeMinifier::$emptyAttributes);

        return $context->setContents(preg_replace_callback(
            '/
                \s                          # first match a whitespace which is an indication if its an attribute
                (' . $booleanAttributes . ')    # match and capture a boolean attribute
                \s*
                =
                \s*
                ([\'"])?                    # optional to use a quote
                (\1|true|false|([\s>"\']))    # match the boolean attribute name again or true|false
                \2?                         # match the quote again
            /xi', function ($match) {
            if (isset($match[4])) {
                return ' ' . $match[1];
            }

            if ($match[3] == 'false') {
                return '';
            }

            return ' ' . $match[1];
        }, $context->getContents()));
    }
}
