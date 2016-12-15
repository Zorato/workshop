<?php

/**
 * Class Storage
 */
class Storage
{

    private $limit = 0;

    private $objects;

    /**
     * Concrete constructor.
     *
     * @param $limit
     */
    public function __construct($limit)
    {
        $this->limit = (int)$limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return mixed
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @param mixed $object
     */
    public function addObject($object)
    {
        $this->objects[] = $object;
    }

}