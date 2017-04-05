<?php

/**
 * Interface Coordinate
 */
interface Coordinate
{

    /**
     * @return float
     */
    public function getAltitude(): float;

    /**
     * @return float
     */
    public function getLatitude(): float;

    /**
     * @return float
     */
    public function getLongitude(): float;
}
