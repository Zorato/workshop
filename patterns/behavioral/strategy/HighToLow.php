<?php

/**
 * Class HighToLow
 */
class HighToLow implements Comparator
{
    /**
     * @inheritDoc
     */
    public function compare($a, $b)
    {
        return $b - $a;
    }

}