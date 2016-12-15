<?php

/**
 * Class Collection
 */
class Collection implements Iterator
{

    /**
     * @var array
     */
    private $items = [];

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

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
    public function current()
    {
        return current($this->items)->method();
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return key($this->items) !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        reset($this->items);
    }


}