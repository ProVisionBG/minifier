<?php


namespace ProVision\Minifier;


use Illuminate\Support\Facades\Config;

class Minifier
{

    const ATTRIBUTE_NAME_REGEX = '[a-zA-Z_:][-a-zA-Z0-9_:.]*';

    /**
     * @var MinifierContext
     */
    private $context;


    function __construct(MinifierContext $context)
    {

        $this->context = $context;

        if (!Config::get('provision_minifier.enable', false) || count(Config::get('provision_minifier.html_minifiers')) < 1) {
            return $context;
        }

        foreach (Config::get('provision_minifier.html_minifiers') as $minifier) {
            $minifier = new $minifier();
            $this->context = $minifier->process($this->context);
        }

    }

    public function get()
    {
        return $this->context->getContents();
    }
}