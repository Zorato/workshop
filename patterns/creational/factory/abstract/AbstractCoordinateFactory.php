<?php declare(strict_types=1);

/**
 * Interface AbstractCoordinateFactory
 */
interface AbstractCoordinateFactory
{

    /**
     * @param $parameter
     * @return CoordinateFactory
     */
    public function create($parameter): CoordinateFactory;

}
