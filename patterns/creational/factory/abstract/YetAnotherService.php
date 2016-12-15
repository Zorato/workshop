<?php declare(strict_types = 1);


/**
 * Class YetAnotherService
 */
class YetAnotherService
{

    /**
     * @var AbstractContractFactory
     */
    private $factory;

    /**
     * AnotherService constructor.
     *
     * @param AbstractContractFactory $factory
     */
    public function __construct(AbstractContractFactory $factory)
    {
        $this->factory = $factory;
    }

    public function method($flag, $argument)
    {
        $factory = $this->factory->createContractFactory($flag);
        $contract = $factory->create($argument . '56');
        $contract->method();
    }

}