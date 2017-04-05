<?php declare(strict_types=1);


/**
 * Class AboveSeaCoordinate
 */
class AboveSeaCoordinate extends PlainCoordinate
{
    /**
     * @inheritDoc
     */
    public function setAltitude(float $altitude)
    {
        if ($altitude < 0) {
            throw new InvalidArgumentException('Altitude must not negative');
        }
        parent::setAltitude($altitude);
    }


}
