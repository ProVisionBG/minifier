<?php
return [
    /**
     * Включване / изключване
     * @var boolean
     */
    'enable' => false,

    /**
     * Автоматично добавя на middleware
     * @var boolean
     */
    'autoload_middleware' => true,

    /**
     * Minifiers
     * @var \ProVision\Minifier\Minifiers\MinifierInterface[]
     */
    'html_minifiers' => [
        \ProVision\Minifier\Minifiers\Html\CommentMinifier::class,
        \ProVision\Minifier\Minifiers\Html\WhitespaceMinifier::class,
        \ProVision\Minifier\Minifiers\Html\AttributeQuoteMinifier::class,
        \ProVision\Minifier\Minifiers\Html\EmptyAttributeMinifier::class,
        \ProVision\Minifier\Minifiers\Html\BooleanAttributeMinifier::class,
        \ProVision\Minifier\Minifiers\Html\JavascriptEventsMinifier::class,
        \ProVision\Minifier\Minifiers\Html\RedundantAttributeMinifier::class,
    ]
];