<?php

interface Connection
{

    /**
     * @return PDO
     */
    public function getConnection();

}