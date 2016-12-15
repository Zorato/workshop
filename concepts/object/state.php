<?php

class A
{

    /**
     * Represents object state.
     *
     * @var mixed
     */
    private $property;

    /**
     * A constructor.
     *
     * @param $property
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * @return mixed
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $property
     */
    public function setProperty($property)
    {
        $this->property = $property;
    }


}