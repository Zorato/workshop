<?php

class FileCache implements Cache
{

    private $folder;

    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    public function has($key)
    {
        return file_exists($this->createFileName($key));
    }

    public function get($key)
    {
        return unserialize(file_get_contents($this->createFileName($key)));
    }

    public function set($key, $value)
    {
        return (bool) file_put_contents($this->createFileName($key), serialize($key));
    }

    private function createFileName($key)
    {
        return rtrim($this->folder, '/') . '/' . sha1($key) . '.cache';
    }

}