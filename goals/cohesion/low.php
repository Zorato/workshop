<?php

/**
 * Class Low
 *
 * Low cohesion example: each method uses only one property, each property only used by one method.
 * Exception: Value Object with getters and setters is ok.
 */
class Low
{

    private $a;

    protected $b;

    protected $c;

    public function methodA()
    {
        return $this->a % 2;
    }

    public function methodB($string)
    {
        return $this->b . ' = ' . $string;
    }

    public function methodC($c)
    {
        $this->c = $c;
    }
}