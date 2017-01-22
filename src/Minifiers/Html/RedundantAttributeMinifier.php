<?php

namespace ProVision\Minifier\Minifiers\Html;

use Illuminate\Support\Collection;
use ProVision\Minifier\MinifierContext;
use ProVision\Minifier\Minifiers\MinifierInterface;
use ProVision\Minifier\Util\Html;

class RedundantAttributeMinifier implements MinifierInterface
{
    /**
     * Attributes which are not needed by the browser.
     *
     * @var array
     */
    protected $redundantAttributes = [
        'script' => [
            'type' => 'text\/javascript',
            'language' => 'javascript',
        ],
        'link' => [
            'type' => 'text\/css',
        ],
        'style' => [
            'type' => 'text\/css',
        ],
        'form' => [
            'method' => 'get',
        ],
    ];

    /**
     * Minify redundant attributes which are not needed by the browser.
     *
     * @param \ProVision\Minifier\MinifyContext $context
     *
     * @return \ProVision\Minifier\MinifyContext
     */
    public function process(MinifierContext $context)
    {
        Collection::make($this->redundantAttributes)->each(function ($attributes, $element) use (&$context) {
            Collection::make($attributes)->each(function ($value, $attribute) use ($element, &$context) {
                $contents = preg_replace_callback(
                    '/
                        ' . $element . '                    # Match the given element
                        ((?!\s*' . $attribute . '\s*=).)*   # Match everything except the given attribute
                        (
                            \s*' . $attribute . '\s*        # Match the attribute
                            =\s*                        # Match the equals sign
                            (["\']?)                    # Match the opening quotes
                            \s*' . $value . '\s*            # Match the value
                            \3?                         # Match the captured opening quotes again
                            \s*
                        )
                    /xis',
                    function ($match) {
                        return $this->removeAttribute($match[0], $match[2]);
                    }, $context->getContents());

                $context->setContents($contents);
            });
        });

        return $context;
    }

    /**
     * Remove the attribute from the element.
     *
     * @param string $element
     * @param string $attribute
     *
     * @return string
     */
    protected function removeAttribute($element, $attribute)
    {
        $replacement = Html::hasSurroundingAttributes($attribute) ? ' ' : '';

        return str_replace($attribute, $replacement, $element);
    }

}
