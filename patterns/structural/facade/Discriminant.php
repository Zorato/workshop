<?php

class Discriminant
{

    private $sub;
    private $mul;

    public function __construct(Subtractor $subtractor, Multiplier $multiplier)
    {
        $this->sub = $subtractor;
        $this->mul = $multiplier;
    }

    /**
     * @param $a
     * @param $b
     * @param $c
     * @return float D = b^2 - 4*a*c
     */
    public function calculate($a, $b, $c)
    {
        $mul = $this->mul;
        $sub = $this->sub;
        return $sub($mul($b, $b), $mul($mul(4, $a), $c));
    }

}