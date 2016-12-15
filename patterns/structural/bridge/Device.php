<?php

/**
 * Class Device
 */
abstract class Device implements DeviceInterface {

    /**
     * @var MessengerInterface
     */
    protected $sender;

    /**
     * @param MessengerInterface $sender
     * @return void
     */
    public function setSender(MessengerInterface $sender)
    {
        $this->sender = $sender;
    }
}