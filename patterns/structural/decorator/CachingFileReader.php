<?php

/**
 * Class CachingFileReader
 */
class CachingFileReader implements FileReader
{

    /**
     * @var FileReader
     */
    private $reader;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * CachingFileReader constructor.
     *
     * @param FileReader $reader
     */
    public function __construct(FileReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @inheritDoc
     */
    public function read($filename)
    {
        if (!isset($this->cache[$filename])) {
            $this->cache[$filename] = $this->reader->read($filename);
        }
        return $this->cache[$filename];
    }


}