<?php

class Adder extends Operator
{
    public function __invoke($a, $b)
    {
        return $this->add($a, $b);
    }

    public function add($a, $b)
    {
        return $a + $b;
    }

}