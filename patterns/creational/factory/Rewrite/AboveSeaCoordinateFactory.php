<?php declare(strict_types=1);

/**
 * Class AboveSeaCoordinateFactory
 */
class AboveSeaCoordinateFactory implements CoordinateFactory
{
    /**
     * @inheritDoc
     */
    public function create(float $latitude, float $longitude, float $altitude): Coordinate
    {
        return new AboveSeaCoordinate($latitude, $longitude, $altitude);
    }

}
