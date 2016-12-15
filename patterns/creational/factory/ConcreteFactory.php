<?php declare(strict_types = 1);


/**
 * Class ConcreteFactory
 */
class ConcreteFactory implements ContractFactory
{
    /**
     * @inheritDoc
     */
    public function create($argument)
    {
        return new Concrete($argument);
    }


}