<?php declare(strict_types = 1);


/**
 * Class MyExtendedConcrete
 */
class MyExtendedConcrete implements Contract
{

    /**
     * @var Contract
     */
    private $concrete;

    /**
     * MyExtendedConcrete constructor.
     *
     * @param $concrete
     */
    public function __construct(Contract $concrete)
    {
        $this->concrete = $concrete;
    }

    public function method()
    {
        echo 'Here comes...' . PHP_EOL;
        $this->concrete->method();
        echo PHP_EOL;
    }

}