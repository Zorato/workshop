<?php

class Subtractor extends Operator
{

    public function __invoke($a, $b)
    {
        return $this->substract($a, $b);
    }

    public function substract($a, $b)
    {
        return $a - $b;
    }

}