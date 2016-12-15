<?php

/**
 * Class YetAnotherCollection
 */
class YetAnotherCollection implements IteratorAggregate
{

    /**
     * @var array
     */
    private $items = [];

    /**
     * @param $item
     * @return void
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new Collection($this->items);
        // or using PHP built-in Iterator class
        // return new ArrayIterator($this->items);
    }

}