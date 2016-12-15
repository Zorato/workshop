<?php

/**
 * Class CouponAwareQuote
 *
 * Quote class that uses coupon to apply discount
 *
 */
class CouponAwareQuote extends Quote {

    /**
     * This one is private, we are encapsulating it from outer-world AND from ancestors.
     *
     * @var string
     */
    private $coupon;

    /**
     * Encapsulated state of the object
     *
     * @var int
     */
    private $discount = 0;

    /**
     * @return string
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount . '%';
    }

    /**
     * @param $coupon
     * @throws InvalidArgumentException
     */
    public function setCoupon($coupon)
    {
        if (!preg_match('/COUP([0-9]{4})/', $coupon, $matches)) {
            throw new InvalidArgumentException('Invalid coupon code provided.');
        }
        $this->coupon = $coupon;
        $this->discount = substr($matches[1], 1, 2);
    }

    /**
     * @param $sum
     * @param $product
     * @return float
     */
    protected function sumProductPrices($sum, $product)
    {
        $price = $product->price * (100 - $this->discount) / 100;
        if ($price < 10) {
            $price = 10;
        }
        return $sum + $price;
    }


}