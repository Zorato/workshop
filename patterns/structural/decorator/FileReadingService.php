<?php

/**
 * Class FileReadingService
 */
class FileReadingService
{

    /**
     * @var FileReader
     */
    private $reader;

    /**
     * FileReadingService constructor.
     *
     * @param FileReader $reader
     */
    public function __construct(FileReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param $filename
     * @return string
     */
    public function process($filename)
    {
        $content = $this->reader->read($filename);
        return preg_replace('/"/', "'", $content);
    }

}