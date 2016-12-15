<?php

/**
 * Class Concrete
 */
final class Concrete implements Contract
{
    private $parameter;

    /**
     * Concrete constructor.
     *
     * @param $parameter
     */
    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    public function method()
    {
        echo '123 ' . $this->parameter;
    }
}