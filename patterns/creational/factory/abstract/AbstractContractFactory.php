<?php

/**
 * Interface AbstractContractFactory
 */
interface AbstractContractFactory
{

    /**
     * @param $flag
     * @return ContractFactory
     */
    public function createContractFactory($flag);

}