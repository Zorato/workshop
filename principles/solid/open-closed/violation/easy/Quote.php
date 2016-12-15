<?php

class Quote
{

    protected $products = [];

    public function charge(PaymentGateway $gateway)
    {
        if ($gateway instanceof SOAPGateway) {
            $gateway->call('capture', $this->calculateTotals());
        } elseif($gateway instanceof HTTPGateway) {
            $gateway->curl('capture', $this->calculateTotals());
        }
    }

    protected function calculateTotals()
    {
        return array_reduce($this->products, function($sum, $product){
            return $sum + $product->getPrice();
        }, 0);
    }

}