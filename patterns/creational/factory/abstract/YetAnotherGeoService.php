<?php declare(strict_types=1);


/**
 * Class YetAnotherGeoService
 */
class YetAnotherGeoService
{

    /**
     * @var AbstractCoordinateFactory
     */
    private $abstractFactory;

    /**
     * YetAnotherGeoService constructor.
     *
     * @param AbstractCoordinateFactory $abstractFactory
     */
    public function __construct(AbstractCoordinateFactory $abstractFactory)
    {
        $this->abstractFactory = $abstractFactory;
    }

    /**
     * @param mixed $parameter Comes from request in runtime.
     * @param $lat
     * @param $lng
     * @return void
     */
    public function execute($parameter, $lat, $lng)
    {
        $factory = $this->abstractFactory->create($parameter);
        $coordinate = $factory->create($lat, $lng, 0);
        // do some calculations with the coordinate
    }

}
