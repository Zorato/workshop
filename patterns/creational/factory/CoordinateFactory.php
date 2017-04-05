<?php declare(strict_types=1);


/**
 * Interface CoordinateFactory
 */
interface CoordinateFactory
{

    /**
     * @param float $latitude
     * @param float $longitude
     * @param float $altitude
     * @return Coordinate
     */
    public function create(float $latitude, float $longitude, float $altitude): Coordinate;

}
