<?php

/**
 * Class NullLogger
 */
class NullLogger implements Logger
{

    /**
     * @inheritDoc
     */
    public function log($message)
    {
        // simply do NOTHING
    }


}