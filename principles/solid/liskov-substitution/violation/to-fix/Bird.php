<?php

abstract class Bird
{

    protected $x;
    protected $y;
    protected $z;

    /**
     * Bird constructor.
     *
     * @param $x
     * @param $y
     * @param $z
     */
    public function __construct($x, $y, $z)
    {
        $this->setLocation($x, $y, $z);
    }

    /**
     * @param $x
     * @param $y
     * @param $z
     * @return void
     */
    public function setLocation($x, $y, $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    /**
     * @param $x
     * @param $y
     * @return void
     */
    public function move($x, $y)
    {
        $this->setLocation($x, $y, $this->z);
    }

    public function fly($z)
    {
        $this->setLocation($this->x, $this->y, $z);
    }

}