<?php

/**
 * Class Product
 *
 * Some base class with common properties for all and every product in our system.
 *
 */
class Product
{

    private $price = 0.0;

    private $sku;

    public function __construct($sku, $price)
    {
        $this->sku = $sku;
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

}