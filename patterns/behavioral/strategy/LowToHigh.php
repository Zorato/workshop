<?php

/**
 * Class LowToHigh
 */
class LowToHigh implements Comparator
{
    /**
     * @inheritDoc
     */
    public function compare($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return $a > $b ? 1 : -1;
    }


}