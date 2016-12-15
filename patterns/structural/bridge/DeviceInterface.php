<?php

/**
 * Interface DeviceInterface
 */
interface DeviceInterface extends MessengerAwareInterface
{

    /**
     * @param $body
     * @return mixed
     */
    public function send($body);

}