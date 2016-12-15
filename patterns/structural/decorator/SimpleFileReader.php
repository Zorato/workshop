<?php declare(strict_types = 1);


/**
 * Class SimpleFileReader
 */
class SimpleFileReader implements FileReader
{
    /**
     * @inheritDoc
     */
    public function read($filename)
    {
        return file_get_contents($filename);
    }
}