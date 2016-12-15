<?php

/**
 * Interface Sorter
 */
interface Comparator
{

    /**
     * Compare 2 elements
     *
     * @param $a
     * @param $b
     * @return int
     */
    public function compare($a, $b);

}