<?php declare(strict_types=1);


/**
 * Class GeoService
 */
class GeoService
{
    /**
     * @var CoordinateFactory
     */
    private $coordinateFactory;

    /**
     * GeoService constructor.
     *
     * @param CoordinateFactory $coordinateFactory
     */
    public function __construct(CoordinateFactory $coordinateFactory)
    {
        $this->coordinateFactory = $coordinateFactory;
    }

    public function calculate($latitude, $longitude)
    {
        $coordinate = $this->coordinateFactory->create($latitude, $longitude, 0);
        $month = date('n');
        if (($coordinate->getLatitude() > 0 && $month > 6) || ($coordinate->getLatitude() < 0 && $month <= 6)) {
            return 'Winter is coming';
        }
        return 'Enjoy your summer. Yet.';
    }

}
