<?php

class Penguin extends Bird
{

    public function setLocation($x, $y, $z)
    {
        if ($this->z != $z) {
            throw new InvalidArgumentException("Penguin can't fly!");
        }
        parent::setLocation($x, $y, $z);
    }

}