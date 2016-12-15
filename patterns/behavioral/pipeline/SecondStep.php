<?php

class SecondStep implements Step
{
    public function handle($payload)
    {
        return $payload * 3;
    }


}