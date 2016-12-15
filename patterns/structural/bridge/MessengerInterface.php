<?php

/**
 * Interface MessengerInterface
 */
interface MessengerInterface
{

    /**
     * @param $message
     * @return mixed
     */
    public function send($message);

}