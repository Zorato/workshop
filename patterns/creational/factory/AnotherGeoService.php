<?php declare(strict_types=1);

/**
 * Class AnotherGeoService
 */
class AnotherGeoService
{

    /**
     * @var CoordinateFactory
     */
    private $coordinateFactory;

    /**
     * AnotherGeoService constructor.
     *
     * @param CoordinateFactory $coordinateFactory
     */
    public function __construct(CoordinateFactory $coordinateFactory)
    {
        $this->coordinateFactory = $coordinateFactory;
    }

    public function isSafeToFly($latitude, $longitude, $altitude)
    {
        $coordinate = $this->coordinateFactory->create($latitude, $longitude, $altitude);
        return $coordinate->getAltitude() > 8848;
    }

}
