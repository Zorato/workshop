<?php

class ThirdStep implements Step
{

    public function handle($payload)
    {
        return sqrt($payload);
    }

}