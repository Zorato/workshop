<?php

class Multiplier extends Operator
{
    public function __invoke($a, $b)
    {
        return $this->multiply($a, $b);
    }

    public function multiply($a, $b)
    {
        return $a * $b;
    }
}