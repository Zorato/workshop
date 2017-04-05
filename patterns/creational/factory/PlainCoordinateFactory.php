<?php declare(strict_types=1);


/**
 * Class PlainCoordinateFactory
 */
class PlainCoordinateFactory implements CoordinateFactory
{

    /**
     * @inheritDoc
     */
    public function create(float $latitude, float $longitude, float $altitude): Coordinate
    {
        return new PlainCoordinate($latitude, $longitude, $altitude);
    }

}
