<?php

/**
 * Class RestrictingFileReader
 *
 * This one is Proxy pattern.
 *
 */
class RestrictingFileReader implements FileReader
{

    /**
     * @var FileReader
     */
    private $reader;

    /**
     * RestrictingFileReader constructor.
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
        if (preg_match('{^/etc/}', $filename)) {
            throw new RuntimeException('Access denied!');
        }
        return $this->reader->read($filename);
    }

}