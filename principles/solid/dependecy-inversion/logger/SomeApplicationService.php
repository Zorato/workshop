<?php

/**
 * Class SomeApplicationService
 */
class SomeApplicationService
{
    /** @var  Logger */
    private $logger;

    /**
     * SomeApplicationService constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Just running some stuff.
     *
     * @param $params
     * @return void
     */
    public function run($params)
    {
        $this->logger->log('Started.');
        // Do something with $params
        $this->logger->log('Done!');
    }
}