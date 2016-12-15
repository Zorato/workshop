<?php declare(strict_types = 1);


/**
 * Interface ContractFactory
 */
interface ContractFactory
{

    /**
     * @param $argument
     * @return Contract
     */
    public function create($argument);


}