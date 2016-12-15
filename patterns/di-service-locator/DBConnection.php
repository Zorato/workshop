<?php

class DBConnection implements Connection
{

    /**
     * @var PDO
     */
    private $connection;

    /**
     * DBConnection constructor.
     *
     * @param string $dsn
     */
    public function __construct($dsn)
    {
        $this->connection = new PDO($dsn);
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

}