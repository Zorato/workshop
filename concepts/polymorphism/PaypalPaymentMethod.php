<?php declare(strict_types = 1);


/**
 * Class PaypalPaymentMethod
 *
 * Uses PayPal API to handle payment
 *
 */
class PaypalPaymentMethod implements PaymentMethod
{
    /**
     * @inheritDoc
     */
    public function authorize($payment)
    {
        // TODO: Implement authorize() method.
    }

    /**
     * @inheritDoc
     */
    public function capture($payment)
    {
        // TODO: Implement capture() method.
    }

}