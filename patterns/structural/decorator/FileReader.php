<?php

/**
 * Interface FileReader
 */
interface FileReader
{

    /**
     * Reads file contents and returns string.
     *
     * @param $filename
     * @return string
     */
    public function read($filename);
}