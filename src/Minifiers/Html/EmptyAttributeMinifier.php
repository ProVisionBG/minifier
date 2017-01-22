<?php

namespace ProVision\Minifier\Minifiers\Html;

use ProVision\Minifier\Minifier;
use ProVision\Minifier\MinifierContext;
use ProVision\Minifier\Minifiers\MinifierInterface;
use ProVision\Minifier\Util\Html;

class EmptyAttributeMinifier implements MinifierInterface
{
    public static $emptyAttributes = [
        "async",
        "autocomplete",
        "autofocus",
        "autoplay",
        "border",
        "challenge",
        "checked",
        "compact",
        "contenteditable",
        "controls",
        "default",
        "defer",
        "disabled",
        "formNoValidate",
        "frameborder",
        "hidden",
        "indeterminate",
        "ismap",
        "loop",
        "multiple",
        "muted",
        "nohref",
        "noresize",
        "noshade",
        "novalidate",
        "nowrap",
        "open",
        "readonly",
        "required",
        "reversed",
        "scoped",
        "scrolling",
        "seamless",
        "selected",
        "sortable",
        "spellcheck",
        "translate"
    ];

    public function __construct()
    {

    }

    /**
     * Execute the minification rule.
     * @param MinifierContext $context
     * @return MinifierContext
     */
    public function process(MinifierContext $context)
    {
        return $context->setContents(preg_replace_callback(
            '/
                (\s*' . Minifier::ATTRIBUTE_NAME_REGEX . '\s*)     # Match the attribute name
                =\s*                                            # Match the equal sign with optional whitespaces
                (["\'])                                         # Match quotes and capture for backreferencing
                \s*                                             # Strange but possible to have a whitespace in an attribute
                \2                                              # Backreference to the matched quote
                \s*
            /x',
            function ($match) {
                if ($this->isBooleanAttribute($match[1])) {
                    return Html::isLastAttribute($match[0]) ? $match[1] : $match[1] . ' ';
                }

                return Html::hasSurroundingAttributes($match[0]) ? ' ' : '';
            }, $context->getContents()));
    }

    /**
     * Check if an attribute is a boolean attribute.
     *
     * @param string $attribute
     *
     * @return bool
     */
    private function isBooleanAttribute($attribute)
    {
        return in_array(trim($attribute), EmptyAttributeMinifier::$emptyAttributes) || Html::isDataAttribute($attribute);
    }

}
