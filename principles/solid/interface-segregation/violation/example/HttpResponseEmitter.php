<?php

class HttpResponseEmitter implements ResponseEmitter
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
        http_response_code($this->content ? 200 : 500);
    }


}