<?php

interface Step
{

    /**
     * @param $payload
     * @return mixed
     */
    public function handle($payload);

}