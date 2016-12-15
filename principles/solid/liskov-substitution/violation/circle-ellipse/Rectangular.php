<?php

class Rectangular
{
    protected $width;
    protected $height;

    /**
     * Rectangular constructor.
     *
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->setWidth($width);
        $this->setHeight($height);
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function area()
    {
        return $this->getHeight() * $this->getWidth();
    }

}