<?php

class Foo {

    private $baz;

    /**
     * @inheritDoc
     */
    public function __construct($serviceLocator)
    {
        $this->baz = $serviceLocator->get('baz');
    }

    public function run()
    {
        // do something with $this->baz
    }

}