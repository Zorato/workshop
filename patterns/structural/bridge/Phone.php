<?php

/**
 * Class Phone
 */
class Phone extends Device
{
    /**
     * @param $body
     * @return void
     */
    public function send($body)
    {
        $message = "<xml>$body</xml>";
        $this->sender->send($message);
    }
    
}