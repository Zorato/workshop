<?php

interface ResponseEmitter
{

    public function sendBody();

    public function sendHeader();

}