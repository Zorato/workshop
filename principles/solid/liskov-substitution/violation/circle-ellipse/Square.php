<?php

class Square extends Rectangular
{

    public function setWidth($width)
    {
        parent::setWidth($width);
        $this->setHeight($width);
    }

    public function setHeight($height)
    {
        $this->setWidth($height);
    }

    public function area()
    {
        return parent::area();
    }

}