<?php
/**
 * Class ContractFactoryFactory
 */
class ContractFactoryFactory implements AbstractContractFactory
{
    public function __construct(Connection $connection)
    {
        // $connection->getConnection()->...
    }

    /**
     * @inheritDoc
     */
    public function createContractFactory($flag)
    {
        if ($flag > 0) {
            return new ConcreteFactory();
        }
        if ($flag < 0) {
            return new MyExtendedConcreteFactory(new ConcreteFactory());
        }

        throw new RuntimeException("Can't decide which factory to use!");
    }

}