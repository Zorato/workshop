<?php

/**
 * Class Service
 */
class Service
{
    /**
     * @var Contract
     */
    private $contract;

    /**
     * Service constructor.
     *
     * @param ContractFactory $factory
     */
    public function __construct(ContractFactory $factory)
    {
        $this->contract = $factory->create('456');
    }

    public function method()
    {
        $this->contract->method();
    }

}