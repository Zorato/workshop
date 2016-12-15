<?php declare(strict_types = 1);


/**
 * Interface MessengerAwareInterface
 */
interface MessengerAwareInterface
{

    /**
     * @param MessengerInterface $sender
     * @return mixed
     */
    public function setSender(MessengerInterface $sender);

}