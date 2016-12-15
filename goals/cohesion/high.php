<?php

/**
 * Class High
 *
 * High cohesion example: each property is used by several methods and each method uses several properties.
 */
class High
{

    private $property1;
    private $property2;

    public function method1()
    {
        return $this->property1 . $this->property2;
    }

    public function method2($property1, $property2)
    {
        $this->property1 = $property1;
        $this->property2 = $property2;
    }

    private $qwerrty;

    public function qwerty()
    {
        return $this->qwerrty;
    }
}