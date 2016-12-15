<?php

/**
 * Class CreditCardPaymentMethod
 *
 * Processes CC data to handle payment
 *
 */
class CreditCardPaymentMethod implements PaymentMethod
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