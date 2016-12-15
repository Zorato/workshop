<?php

/**
 * Class Sorter
 */
class Sorter
{

    /**
     * @var Comparator
     */
    private $comparator;

    /**
     * Sorter constructor.
     *
     * @param Comparator $comparator
     */
    public function __construct(Comparator $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * Sorts array.
     *
     * @param array $array
     * @return array
     */
    public function sort(array $array)
    {
        usort($array, [$this->comparator, 'compare']);

        return $array;
    }

}