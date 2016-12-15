<?php

/**
 * Class AnotherService
 */
class AnotherService
{

    /**
     * @var ContractFactory
     */
    private $factory;

    /**
     * AnotherService constructor.
     *
     * @param ContractFactory $factory
     */
    public function __construct(ContractFactory $factory)
    {
        $this->factory = $factory;
    }

    public function method($argument)
    {
        $contract = $this->factory->create($argument . '56');
        $contract->method();
    }

}