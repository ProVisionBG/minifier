<?php

namespace ProVision\Minifier\Minifiers;

use ProVision\Minifier\MinifierContext;

interface MinifierInterface
{
    /**
     * Process the payload.
     * @param MinifierContext $context
     * @return MinifierContext
     */
    public function process(MinifierContext $context);

}
