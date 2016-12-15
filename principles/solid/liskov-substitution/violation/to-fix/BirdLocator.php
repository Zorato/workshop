<?php

class BirdLocator
{

    public function relocate(Bird $bird, $coordinates)
    {
        list($x, $y, $z) = $coordinates;
        if ($bird instanceof Penguin) {
            $bird->move($x, $y);
        } else {
            $bird->setLocation($x, $y, $z);
        }
    }

}