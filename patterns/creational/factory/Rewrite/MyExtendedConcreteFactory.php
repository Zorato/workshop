<?php declare(strict_types = 1);


/**
 * Class MyExtendedConcreteFactory
 */
class MyExtendedConcreteFactory implements ContractFactory
{

    /**
     * @var ContractFactory
     */
    private $factory;

    /**
     * MyExtendedConcreteFactory constructor.
     *
     * @param $factory
     */
    public function __construct(ContractFactory $factory = null)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function create($argument)
    {
        return new MyExtendedConcrete($this->factory ? $this->factory->create($argument) : new Storage($argument));
    }

}