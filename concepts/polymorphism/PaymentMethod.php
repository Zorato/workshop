<?php

/**
 * Interface PaymentMethod
 *
 * Interface any payment method MUST implement
 *
 */
interface PaymentMethod
{

    /**
     * Authorizes (reserve/block) $payment amount on customer's account
     *
     * @param $payment
     */
    public function authorize($payment);

    /**
     * Captures (actually transfer funds) $payment amount from customer's account
     *
     * @param $payment
     */
    public function capture($payment);

}