<?php

/**
 * Class Skype
 */
class Skype implements MessengerInterface
{
    /**
     * @inheritDoc
     */
    public function send($message)
    {
        return true; // This one is dummy so far. Will be enabled in the next release.
    }


}