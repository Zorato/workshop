<?php

/**
 * Class FiniteProduct
 *
 * Even more specific product.
 * Probably, classes derived from NamedProduct instead of this one are infinite (e.g. virtual).
 */
class FiniteProduct extends NamedProduct
{

    protected $stock = 0;

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

}