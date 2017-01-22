<?php

namespace ProVision\Minifier;


class MinifierContext
{

    /**
     * @var string
     */
    private $contents;


    public function __construct($html)
    {
        $this->setContents($html);
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     *
     * @return $this
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }


}
