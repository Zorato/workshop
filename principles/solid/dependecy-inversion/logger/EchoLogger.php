<?php

/**
 * Class SimpleLogger
 */
class EchoLogger implements Logger
{
    /**
     * @inheritDoc
     */
    public function log($message)
    {
        fwrite(fopen('php://output', 'w'), $message);
    }

}