<?php declare(strict_types=1);


/**
 * Class PlainCoordinate
 */
class PlainCoordinate implements Coordinate
{

    /**
     * @var float
     */
    private $altitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * PlainCoordinate constructor.
     *
     * @param float $latitude
     * @param float $longitude
     * @param float $altitude
     */
    public function __construct(float $latitude, float $longitude, float $altitude = .0)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
        $this->setAltitude($altitude);
    }

    /**
     * @return float
     */
    public function getAltitude(): float
    {
        return $this->altitude;
    }

    /**
     * @param float $altitude
     */
    public function setAltitude(float $altitude)
    {
        $this->altitude = $altitude;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @throws InvalidArgumentException
     */
    public function setLatitude(float $latitude)
    {
        if ($latitude > 90 || $latitude < -90) {
            throw new InvalidArgumentException('Invalid latitude');
        }
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @throws InvalidArgumentException
     */
    public function setLongitude(float $longitude)
    {
        if ($longitude > 180 || $longitude < 180) {
            throw new InvalidArgumentException('Invalid longitude');
        }
        $this->longitude = $longitude;
    }

}
