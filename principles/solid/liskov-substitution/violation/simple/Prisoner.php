<?php

/**
 * Class Prisoner
 */
class Prisoner extends Person
{

    public function walkNorth($meters)
    {

    }

    public function walkEast($meters)
    {
        throw new LogicException("I can't, I'm locked in a cell!");
    }


}