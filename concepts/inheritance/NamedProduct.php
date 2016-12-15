<?php

/**
 * Class NamedProduct
 *
 * Some more specific product.
 * Name property added.
 */
class NamedProduct extends Product
{

    private $name = '';

    public function __construct($sku, $price, $name)
    {
        parent::__construct($sku, $price);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}