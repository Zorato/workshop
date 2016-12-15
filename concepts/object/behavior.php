<?php

class A
{

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
     * Behavior example.
     *
     * @return int
     */
    public function method()
    {
        $this->property += 5;
        return $this->property % 3;
    }

}