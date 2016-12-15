<?php declare(strict_types = 1);


/**
 * Class Tablet
 */
class Tablet extends Device
{
    /**
     * @param $body
     * @return void
     */
    public function send($body)
    {
        $message = json_encode(['body' => $body]);
        $this->sender->send($message);
    }
}