<?php

class FirstStep implements Step
{
    public function handle($payload)
    {
        return $payload + 5;
    }

}