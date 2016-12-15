<?php

/**
 * Class Quote
 *
 * Some class representing shopping cart.
 *
 */
class Quote {

    protected $products = [];

    /**
     * @param $product
     * @return void
     */
    public function addProduct($product)
    {
        $this->products[] = $product;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return array_reduce($this->products, [$this, 'sumProductPrices'], 0);
    }

    /**
     * @param $sum
     * @param $product
     * @return float
     */
    protected function sumProductPrices($sum, $product) {
        return $sum + $product->price;
    }

}