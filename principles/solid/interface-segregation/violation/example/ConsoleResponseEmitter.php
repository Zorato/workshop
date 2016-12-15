<?php

class ConsoleResponseEmitter implements ResponseEmitter
{

    protected $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function sendBody()
    {
        echo $this->content;
    }

    public function sendHeader()
    {
        // nothing here
    }

}