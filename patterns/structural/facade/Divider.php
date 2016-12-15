<?php

class Divider extends Operator
{
    public function __invoke($a, $b)
    {
        return $this->divide($a, $b);
    }

    public function divide($a, $b)
    {
        if ($b == 0) {
            throw new LogicException('Division by zero');
        }

        return $a / $b;
    }

}